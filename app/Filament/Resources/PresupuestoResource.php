<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresupuestoResource\Pages;
use App\Filament\Resources\PresupuestoResource\RelationManagers;
use App\Models\Agencia;
use App\Models\Insumo;
use App\Models\Mobiliario;
use App\Models\CategoriaInsumo;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\Sector;
use App\Services\PresupuestoEntregaService;
use App\Services\StockReservaService;
use App\Support\PresupuestoAuthorization;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PresupuestoResource extends BaseResource
{
    protected static ?string $model = Presupuesto::class;
    protected static ?string $navigationIcon   = 'heroicon-o-document-text';
    protected static ?string $navigationGroup  = 'Operaciones';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $modelLabel       = 'Presupuesto';
    protected static ?string $pluralModelLabel = 'Presupuestos';

    public static function clonarTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('clonar')
            ->label('Clonar')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('clonePresupuesto', $record))
            ->authorize('clonePresupuesto')
            ->requiresConfirmation()
            ->modalHeading('Clonar presupuesto')
            ->modalDescription('Se creará un nuevo presupuesto en borrador con los datos generales y los ítems de este presupuesto.')
            ->action(function (Presupuesto $record) {
                $clon = $record->clonarConItems();

                Notification::make()
                    ->success()
                    ->title("Presupuesto clonado: {$clon->codigo}")
                    ->send();

                return redirect(static::getUrl('edit', ['record' => $clon]));
            });
    }

    public static function clonarPageAction(\Closure $getRecord): Actions\Action
    {
        return Actions\Action::make('clonar')
            ->label('Clonar')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('clonePresupuesto', $getRecord()))
            ->authorize(fn (): bool => auth()->user()?->can('clonePresupuesto', $getRecord()) ?? false)
            ->requiresConfirmation()
            ->modalHeading('Clonar presupuesto')
            ->modalDescription('Se creará un nuevo presupuesto en borrador con los datos generales y los ítems de este presupuesto.')
            ->action(function () use ($getRecord) {
                $clon = $getRecord()->clonarConItems();

                Notification::make()
                    ->success()
                    ->title("Presupuesto clonado: {$clon->codigo}")
                    ->send();

                return redirect(static::getUrl('edit', ['record' => $clon]));
            });
    }

    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function layoutFormSchema(Presupuesto $record): array
    {
        $medias = $record->layoutMedias();

        if ($medias->count() <= 1) {
            return [];
        }

        return [
            Forms\Components\Radio::make('media_id')
                ->label('Plano / Layout')
                ->options($medias->mapWithKeys(
                    fn (Media $media) => [$media->id => $media->name]
                ))
                ->required(),
        ];
    }

    public static function handleLayoutAction(Presupuesto $record, array $data, Actions\Action|Tables\Actions\Action $action): void
    {
        $medias = $record->layoutMedias();

        if ($medias->isEmpty()) {
            Notification::make()
                ->warning()
                ->title('No hay planos cargados')
                ->send();

            return;
        }

        $media = $medias->count() === 1
            ? $medias->first()
            : $medias->firstWhere('id', (int) ($data['media_id'] ?? 0));

        if (! $media) {
            Notification::make()
                ->warning()
                ->title('No hay planos cargados')
                ->send();

            return;
        }

        $action->getLivewire()->js('window.open(' . json_encode(url($media->getUrl())) . ", '_blank')");
    }

    public static function layoutPageAction(\Closure $getRecord): Actions\Action
    {
        return Actions\Action::make('layout')
            ->label('Layout')
            ->icon('heroicon-o-map')
            ->color('primary')
            ->modalHeading('Elegir plano')
            ->modalSubmitActionLabel('Abrir')
            ->modalHidden(fn (): bool => $getRecord()->layoutMedias()->count() <= 1)
            ->form(fn (): array => static::layoutFormSchema($getRecord()))
            ->action(function (array $data, Actions\Action $action) use ($getRecord): void {
                static::handleLayoutAction($getRecord(), $data, $action);
            });
    }

    public static function layoutTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('layout')
            ->label('Layout')
            ->icon('heroicon-o-map')
            ->color('primary')
            ->modalHeading('Elegir plano')
            ->modalSubmitActionLabel('Abrir')
            ->modalHidden(fn (Presupuesto $record): bool => $record->layoutMedias()->count() <= 1)
            ->form(fn (Presupuesto $record): array => static::layoutFormSchema($record))
            ->action(function (Presupuesto $record, array $data, Tables\Actions\Action $action): void {
                static::handleLayoutAction($record, $data, $action);
            });
    }

    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function pdfPreciosFormSchema(): array
    {
        return [
            Forms\Components\Radio::make('mostrar_precios')
                ->label('Precios en el PDF')
                ->options([
                    '1' => 'Precios visibles',
                    '0' => 'Precios ocultos',
                ])
                ->default('1')
                ->required(),
        ];
    }

    public static function pdfTableAction(string $label = 'Exportar PDF'): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('pdf')
            ->label($label)
            ->icon('heroicon-o-document-text')
            ->color('danger')
            ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('export', $record))
            ->authorize('export')
            ->form(static::pdfPreciosFormSchema())
            ->action(function (Presupuesto $record, array $data, Tables\Actions\Action $action): void {
                $url = route('presupuesto.pdf.viewer', [
                    'presupuesto' => $record->id,
                    'precios' => (int) $data['mostrar_precios'],
                ]);

                $action->getLivewire()->js('window.open(' . json_encode($url) . ", '_blank')");
            });
    }

    public static function pdfPageAction(\Closure $getRecord, string $label = 'PDF', string $name = 'pdf'): Actions\Action
    {
        return Actions\Action::make($name)
            ->label($label)
            ->icon('heroicon-o-document-text')
            ->color('danger')
            ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('export', $getRecord()))
            ->authorize('export')
            ->form(static::pdfPreciosFormSchema())
            ->action(function (array $data, Actions\Action $action) use ($getRecord): void {
                $url = route('presupuesto.pdf.viewer', [
                    'presupuesto' => $getRecord()->id,
                    'precios' => (int) $data['mostrar_precios'],
                ]);

                $action->getLivewire()->js('window.open(' . json_encode($url) . ", '_blank')");
            });
    }

    public static function imprimirPageAction(\Closure $getRecord): Actions\Action
    {
        return static::pdfPageAction($getRecord, 'Imprimir', 'imprimir')
            ->icon('heroicon-o-printer')
            ->color('gray');
    }

    // ─── Form ─────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Información del Presupuesto')
                ->schema([
                    Forms\Components\TextInput::make('codigo')
                        ->label('Código')
                        ->placeholder('Auto-generado al guardar')
                        ->disabled()
                        ->dehydrated(false)
                        ->visibleOn('edit')
                        ->columnSpan(1),

                    Forms\Components\Placeholder::make('estado_display')
                        ->label('Estado actual')
                        ->content(fn (?Presupuesto $record): string =>
                            $record
                                ? (Presupuesto::ESTADOS[$record->estado] ?? $record->estado)
                                : 'Borrador'
                        )
                        ->visibleOn('edit')
                        ->columnSpan(1),

                    Forms\Components\Placeholder::make('version_display')
                        ->label('Versión')
                        ->content(fn (?Presupuesto $record): string =>
                            $record ? 'v' . $record->version : 'v1'
                        )
                        ->visibleOn('edit')
                        ->columnSpan(1),

                    Forms\Components\Select::make('agencia_id')
                        ->label('Agencia')
                        ->options(function () {
                            return Agencia::with('proyecto.marca')
                                ->where('activo', true)
                                ->orderBy('nombre')
                                ->get()
                                ->mapWithKeys(fn ($a) => [
                                    $a->id => $a->nombre
                                        . ' — ' . ($a->proyecto?->codigo_interno ?? '—')
                                        . ' (' . ($a->proyecto?->marca?->nombre ?? '—') . ')',
                                ]);
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->helperText('La marca y el listado de mobiliarios se obtienen del proyecto asignado a la agencia.')
                        ->columnSpan(1),

                    Forms\Components\Select::make('responsable_id')
                        ->label('Responsable')
                        ->relationship('responsable', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(fn () => auth()->id())
                        ->columnSpan(1),
                    Forms\Components\DatePicker::make('fecha_vencimiento')
                        ->label('Posible fecha de entrega')
                        ->default(now()->addDays(45))
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('fecha_emision')
                        ->label('Fecha de Emisión')
                        ->required()
                        ->default(now())
                        ->columnSpan(1),

                    Forms\Components\Textarea::make('metodo_pago')
                        ->label('Bases y condiciones')
                        ->helperText('Cada línea se muestra como un ítem en el PDF comercial')
                        ->default(fn (): string => Presupuesto::textoBasesCondicionesDefault())
                        ->rows(8)
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Checkbox::make('logistica_instalacion_propia')
                        ->label('Logística e instalación propia')
                        ->default(true)
                        ->live()
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('logistica_costo')
                        ->label('Costo')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    Forms\Components\Textarea::make('logistica_leyenda')
                        ->label('Leyenda para planilla impresa')
                        ->rows(3)
                        ->nullable()
                        ->required(fn (Get $get): bool => ! $get('logistica_instalacion_propia'))
                        ->visible(fn (Get $get): bool => ! $get('logistica_instalacion_propia')),
                ])
                ->columns(3),

            Forms\Components\Section::make('Items del Presupuesto')
                ->description('Arrastrá los ítems (o usá los botones subir/bajar) para definir el orden global. El PDF agrupa por sector y, dentro de cada uno, los muestra de menor a mayor según este orden.')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Forms\Components\Select::make('_item_selector')
                                ->label('Mobiliario / Silla')
                                ->options(function (Get $get) {
                                    $agenciaId = $get('../../agencia_id');

                                    $agencia = $agenciaId
                                        ? Agencia::with('proyecto.marca')->find($agenciaId)
                                        : null;
                                    $proyecto = $agencia?->proyecto;
                                    $marcaId = $proyecto?->marca_id;

                                    // Mobiliarios: del proyecto si hay agencia, o todos activos
                                    if ($proyecto) {
                                        $mobiliarios = $proyecto->mobiliarios()
                                            ->where('estado', 'activo')
                                            ->orderBy('nombre')
                                            ->get()
                                            ->mapWithKeys(fn ($m) => [
                                                'mob_' . $m->id => "[{$m->codigo_interno}] {$m->nombre}",
                                            ])
                                            ->toArray();
                                    } elseif (! $agenciaId) {
                                        $mobiliarios = \App\Models\Mobiliario::where('estado', 'activo')
                                            ->orderBy('nombre')
                                            ->get()
                                            ->mapWithKeys(fn ($m) => [
                                                'mob_' . $m->id => "[{$m->codigo_interno}] {$m->nombre}",
                                            ])
                                            ->toArray();
                                    } else {
                                        $mobiliarios = [];
                                    }

                                    $sillas = [];

                                    if ($marcaId) {
                                        $sillas = Insumo::query()
                                            ->whereHas('categoriasInsumo', fn ($q) =>
                                                $q->where('nombre', 'like', '%silla%')
                                            )
                                            ->where('activo', true)
                                            ->whereHas('marcasSilla', fn ($q) => $q->where('marca_id', $marcaId))
                                            ->with(['marcasSilla' => fn ($q) => $q->where('marca_id', $marcaId)])
                                            ->orderBy('nombre')
                                            ->get()
                                            ->mapWithKeys(function (Insumo $insumo) use ($marcaId): array {
                                                $datos = $insumo->nombreYCodigoParaMarca($marcaId);
                                                $label = filled($datos['codigo'])
                                                    ? "[SILLA] [{$datos['codigo']}] {$datos['nombre']}"
                                                    : "[SILLA] {$datos['nombre']}";

                                                return ['ins_' . $insumo->id => $label];
                                            })
                                            ->toArray();
                                    }

                                    return array_merge($mobiliarios, $sillas);
                                })
                                ->searchable()
                                ->required()
                                ->dehydrated(false)
                                ->live()
                                ->afterStateHydrated(function ($component, $state) {
                                    // El estado ya viene como string, no necesita getKey()
                                    $component->state($state);
                                })
                                ->formatStateUsing(function ($state, $record): ?string {
                                    if ($record?->mobiliario_id) return 'mob_' . $record->mobiliario_id;
                                    if ($record?->insumo_id)     return 'ins_' . $record->insumo_id;
                                    return $state;
                                })
                                ->afterStateUpdated(function ($state, Forms\Set $set): void {
                                    if (!$state) return;
                                    if (str_starts_with($state, 'mob_')) {
                                        $set('mobiliario_id', (int) substr($state, 4));
                                        $set('insumo_id', null);
                                    } elseif (str_starts_with($state, 'ins_')) {
                                        $set('insumo_id', (int) substr($state, 4));
                                        $set('mobiliario_id', null);
                                    }
                                })
                                ->columnSpan(2),

                            Forms\Components\Hidden::make('mobiliario_id'),
                            Forms\Components\Hidden::make('insumo_id'),

                            Forms\Components\Select::make('sector_id')
                                ->label('Sector')
                                ->options(fn () => Sector::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                                ->searchable()
                                ->nullable()
                                ->placeholder('Sin sector')
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('nombre')
                                        ->label('Nombre')
                                        ->required()->maxLength(100),
                                    Forms\Components\Toggle::make('activo')
                                        ->label('Activo')
                                        ->default(true),
                                ])
                                ->createOptionUsing(fn (array $data) => Sector::create($data)->getKey())
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->integer()
                                ->minValue(1)
                                ->default(1)
                                ->required()
                                ->live()
                                ->columnSpan(1),

                            Forms\Components\Placeholder::make('_stock_info')
                                ->label('Stock / Fabricación estimada')
                                ->content(function (Get $get): string {
                                    $mobiliarioId = $get('mobiliario_id');
                                    $cantidad     = max(1, (int) ($get('cantidad') ?? 1));

                                    if (! $mobiliarioId) {
                                        return '—';
                                    }

                                    $stock      = (int) (Mobiliario::find($mobiliarioId)?->stock_actual ?? 0);
                                    $desdeStock = min($cantidad, $stock);
                                    $aFabricar  = $cantidad - $desdeStock;

                                    return "Stock disponible: {$stock} · Desde stock: {$desdeStock} · A fabricar: {$aFabricar}";
                                })
                                ->visible(fn (Get $get): bool => filled($get('mobiliario_id')))
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('precio_unitario')
                                ->label('Precio Unit. $')
                                ->numeric()
                                ->minValue(0)
                                ->nullable()
                                ->placeholder('Opcional')
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('descripcion_override')
                                ->label('Descripción personalizada')
                                ->nullable()
                                ->placeholder('Dejar vacío para usar la descripción del mobiliario')
                                ->columnSpan(4),

                            Forms\Components\Textarea::make('observaciones')
                                ->label('Observaciones')
                                ->rows(2)
                                ->nullable()
                                ->columnSpan(2),

                            Forms\Components\Textarea::make('notas_manuales')
                                ->label('Notas para planilla impresa')
                                ->rows(2)
                                ->nullable()
                                ->placeholder('Anotaciones que aparecerán en el PDF')
                                ->columnSpan(2),

                        ])
                        ->columns(4)
                        ->reorderable()
                        ->reorderableWithDragAndDrop()
                        ->reorderableWithButtons()
                        ->orderColumn('orden')
                        ->addActionLabel('+ Agregar mobiliario')
                        ->defaultItems(0)
                        ->cloneable()
                        ->truncateItemLabel(false)
                        ->itemLabel(fn (array $state): ?HtmlString => static::etiquetaItemRepeater($state))
                        ->collapsible()
                        ->collapsed(),
                ]),

            Forms\Components\Section::make('Observaciones y Notas')
                ->schema([
                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones generales')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('notas_internas')
                        ->label('Notas internas (no aparecen en PDF)')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsible(),
        ]);
    }

    // ─── Infolist ─────────────────────────────────────────────────────────────

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Información del presupuesto')->schema([
                Infolists\Components\TextEntry::make('codigo')
                    ->label('Código')
                    ->badge()
                    ->color('primary'),
                Infolists\Components\TextEntry::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => Presupuesto::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => Presupuesto::ESTADOS[$state] ?? $state),
                Infolists\Components\TextEntry::make('version')
                    ->label('Versión')
                    ->formatStateUsing(fn (int $state): string => 'v' . $state),
                Infolists\Components\TextEntry::make('agencia.nombre')
                    ->label('Agencia'),
                Infolists\Components\TextEntry::make('proyecto.codigo_interno')
                    ->label('Proyecto')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('proyecto.marca.nombre')
                    ->label('Marca')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('responsable.name')
                    ->label('Responsable'),
                Infolists\Components\TextEntry::make('fecha_emision')
                    ->label('Fecha de emisión')
                    ->date('d/m/Y'),
                Infolists\Components\TextEntry::make('fecha_vencimiento')
                    ->label('Fecha posible de entrega')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('metodo_pago')
                    ->label('Bases y condiciones')
                    ->listWithLineBreaks()
                    ->columnSpanFull(),
                Infolists\Components\IconEntry::make('logistica_instalacion_propia')
                    ->label('Logística e instalación propia')
                    ->boolean(),
                Infolists\Components\TextEntry::make('leyenda_logistica_efectiva')
                    ->label('Leyenda logística'),
                Infolists\Components\TextEntry::make('logistica_costo')
                    ->label('Costo logística')
                    ->money('ARS'),
                Infolists\Components\TextEntry::make('aprobadoPor.name')
                    ->label('Aprobado por')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('aprobado_at')
                    ->label('Aprobado el')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),
            ])->columns(3),

            Infolists\Components\Section::make('Resumen de ítems')->schema([
                Infolists\Components\TextEntry::make('resumen_items')
                    ->label('Estado de finalización')
                    ->getStateUsing(function (Presupuesto $record): string {
                        $items = $record->items;
                        $total = $items->count();
                        $finalizados = $items->filter(fn ($item) => $item->estaFinalizado())->count();
                        $pendientes = $total - $finalizados;

                        return "{$total} ítems — {$finalizados} finalizados, {$pendientes} pendientes";
                    }),
                Infolists\Components\TextEntry::make('progreso_entrega')
                    ->label('Progreso de entrega')
                    ->badge()
                    ->color('info'),
                Infolists\Components\TextEntry::make('resumen_entrega')
                    ->label('Estado de entrega'),
            ]),

            Infolists\Components\Section::make('Observaciones')->schema([
                Infolists\Components\TextEntry::make('observaciones')
                    ->label('Observaciones generales')
                    ->placeholder('—')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('notas_internas')
                    ->label('Notas internas')
                    ->placeholder('—')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    // ─── Table ────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\ImageColumn::make('proyecto.marca.logo')
                    ->label('Logo')
                    ->disk('public')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit:contain; background:#f3f4f6;']),

                Tables\Columns\TextColumn::make('proyecto.marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('agencia.nombre')
                    ->label('Agencia')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('agencia.provincia.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('agencia.ciudad.nombre')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => Presupuesto::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => Presupuesto::ESTADOS[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_emision')
                    ->label('Emisión')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (Presupuesto $record): string => static::getUrl('view', ['record' => $record]))
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(Presupuesto::ESTADOS),

                Tables\Filters\SelectFilter::make('agencia_id')
                    ->label('Agencia')
                    ->relationship('agencia', 'nombre')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->relationship('proyecto.marca', 'nombre')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('provincia_id')
                    ->label('Provincia')
                    ->relationship('agencia.provincia', 'nombre')
                    ->searchable()
                    ->preload(),

            ])
            ->actions([
                // ── Botones de estado visibles directamente en la fila ──────
                Tables\Actions\Action::make('enviarACliente')
                    ->label('Enviar a Cliente')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->button()
                    ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('changeState', $record) && $record->puedeEnviarACliente())
                    ->authorize('changeState')
                    ->requiresConfirmation()
                    ->modalHeading('Enviar presupuesto a cliente')
                    ->modalDescription('Se van a congelar los precios de los mobiliarios e insumos/sillas del presupuesto. Dejarán de seguir el precio de lista futuro.')
                    ->action(function (Presupuesto $record): void {
                        $record->cambiarEstado('enviado_a_cliente');
                        Notification::make()->success()->title('Presupuesto enviado a cliente. Precios congelados.')->send();
                    }),

                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('changeState', $record) && $record->puedeAprobar())
                    ->authorize('changeState')
                    ->requiresConfirmation()
                    ->modalHeading('Aprobar presupuesto')
                    ->modalDescription('Se reservará el stock de insumos y se generará una orden de compra si hay faltantes.')
                    ->action(function (Presupuesto $record): void {
                        $record->cambiarEstado('confirmado');
                        Notification::make()->success()->title('Presupuesto aprobado y confirmado. Stock reservado.')->send();
                    }),

                Tables\Actions\Action::make('marcarPagado')
                    ->label('Marcar pagado')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->button()
                    ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('changeState', $record) && $record->estado === 'confirmado')
                    ->authorize('changeState')
                    ->requiresConfirmation()
                    ->modalHeading('Registrar pago')
                    ->modalDescription('Se registrará el pago del presupuesto. El stock se descuenta al confirmar la finalización de cada ítem.')
                    ->action(function (Presupuesto $record): void {
                        $record->cambiarEstado('pagado');
                        Notification::make()->success()->title('Presupuesto marcado como pagado.')->send();
                    }),

                Tables\Actions\Action::make('entregarCompleto')
                    ->label('Entregar completo')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->button()
                    ->visible(fn (Presupuesto $record): bool =>
                        PresupuestoAuthorization::canForRecord('registerDelivery', $record)
                        && $record->puedeRegistrarEntrega()
                        && $record->items()->whereNull('entregado_at')->exists()
                    )
                    ->authorize('registerDelivery')
                    ->requiresConfirmation()
                    ->modalHeading('Entregar presupuesto completo')
                    ->modalDescription('Se marcarán todos los ítems como entregados.')
                    ->action(function (Presupuesto $record): void {
                        app(PresupuestoEntregaService::class)->marcarEntregaCompleta($record);
                        Notification::make()->success()->title('Presupuesto entregado completamente')->send();
                    }),

                Tables\Actions\Action::make('entregarParcial')
                    ->label('Entregar parcial')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('warning')
                    ->button()
                    ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('registerDelivery', $record) && $record->puedeRegistrarEntrega())
                    ->authorize('registerDelivery')
                    ->fillForm(fn (Presupuesto $record): array => [
                        'items_entregados' => $record->items()
                            ->whereNotNull('entregado_at')
                            ->pluck('id')
                            ->all(),
                    ])
                    ->form([
                        Forms\Components\CheckboxList::make('items_entregados')
                            ->label('Ítems entregados')
                            ->options(fn (Presupuesto $record): array => $record->items()
                                ->orderBy('orden')
                                ->get()
                                ->mapWithKeys(fn (PresupuestoItem $item): array => [
                                    $item->id => "[{$item->item_codigo}] {$item->item_nombre}",
                                ])
                                ->all())
                            ->columns(1),
                    ])
                    ->action(function (Presupuesto $record, array $data): void {
                        app(PresupuestoEntregaService::class)->marcarEntregaParcial(
                            $record,
                            $data['items_entregados'] ?? [],
                        );

                        Notification::make()
                            ->success()
                            ->title(match ($record->fresh()->estado) {
                                'entregado'         => 'Presupuesto entregado completamente',
                                'entregado_parcial' => 'Entrega parcial registrada',
                                default             => 'Entrega actualizada',
                            })
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()
                    ->button()
                    ->openUrlInNewTab(),

                // ── Resto de acciones en el menú desplegable ────────────────
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    static::clonarTableAction(),

                    static::pdfTableAction(),

                    static::layoutTableAction(),

                    Tables\Actions\Action::make('excel')
                        ->label('Exportar Excel')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('export', $record))
                        ->authorize('export')
                        ->url(fn (Presupuesto $record) => route('presupuesto.excel', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('produccionExcel')
                        ->label('Exportar Excel Producción')
                        ->icon('heroicon-o-table-cells')
                        ->color('info')

                        ->url(fn (Presupuesto $record) => route('presupuesto.produccion.excel', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('enviarRevision')
                        ->label('Enviar a Revisión')
                        ->icon('heroicon-o-arrow-right-circle')
                        ->color('warning')
                        ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('changeState', $record) && $record->puedeEnviarARevision())
                        ->authorize('changeState')
                        ->requiresConfirmation()
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('en_revision');
                            Notification::make()->success()->title('Enviado a revisión')->send();
                        }),

                    Tables\Actions\Action::make('rechazar')
                        ->label('Rechazar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('changeState', $record) && $record->puedeRechazar())
                        ->authorize('changeState')
                        ->form([
                            Forms\Components\Textarea::make('comentario')
                                ->label('Motivo del rechazo')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (Presupuesto $record, array $data): void {
                            $record->cambiarEstado('rechazado', $data['comentario']);
                            Notification::make()->warning()->title('Presupuesto rechazado')->send();
                        }),

                    Tables\Actions\Action::make('cancelar')
                        ->label('Cancelar')
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->color('gray')
                        ->visible(fn (Presupuesto $record): bool => PresupuestoAuthorization::canForRecord('changeState', $record) && $record->puedeCancelar())
                        ->authorize('changeState')
                        ->requiresConfirmation()
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('cancelado');
                            Notification::make()->info()->title('Presupuesto cancelado')->send();
                        }),

                    Tables\Actions\Action::make('nuevaVersion')
                        ->label('Nueva Versión')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->visible(fn (Presupuesto $record): bool =>
                            PresupuestoAuthorization::canForRecord('changeState', $record)
                            && in_array($record->estado, ['confirmado', 'rechazado'])
                        )
                        ->authorize('changeState')
                        ->form([
                            Forms\Components\Textarea::make('motivo')
                                ->label('Motivo de la nueva versión')
                                ->rows(2)
                                ->nullable(),
                        ])
                        ->action(function (Presupuesto $record, array $data): void {
                            $record->crearVersion($data['motivo'] ?? null);
                            $nuevoNumero = $record->version + 1;
                            $record->update(['version' => $nuevoNumero]);
                            $record->cambiarEstado('borrador', 'Nueva versión: v' . $nuevoNumero);
                            Notification::make()->success()
                                ->title("Nueva versión creada: v{$nuevoNumero}")
                                ->send();
                        }),

                    Tables\Actions\Action::make('recalcularInsumos')
                        ->label('Recalcular insumos')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->visible(false) // Oculto hasta nuevo aviso
                        ->authorize('changeState')
                        ->requiresConfirmation()
                        ->modalHeading('Recalcular insumos del presupuesto')
                        ->modalDescription('Se recalculará la demanda de insumos con la composición técnica actual de los mobiliarios, considerando solo los ítems no finalizados. Se ajustarán las reservas activas (crear, actualizar o liberar). No se descuenta stock ni se modifican las órdenes de compra.')
                        ->action(function (Presupuesto $record): void {
                            try {
                                $resumen = app(StockReservaService::class)->recalcularInsumos($record);

                                Notification::make()->success()
                                    ->title('Insumos recalculados')
                                    ->body("Reservas creadas: {$resumen['creadas']} · actualizadas: {$resumen['actualizadas']} · liberadas: {$resumen['liberadas']}")
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()->danger()
                                    ->title('No se pudieron recalcular los insumos')
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ─── Relation managers ────────────────────────────────────────────────────

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
            RelationManagers\VersionesRelationManager::class,
            RelationManagers\HistorialPresupuestoRelationManager::class,
        ];
    }

    // ─── Pages ────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPresupuestos::route('/'),
            'create' => Pages\CreatePresupuesto::route('/create'),
            'view'   => Pages\ViewPresupuesto::route('/{record}'),
            'edit'   => Pages\EditPresupuesto::route('/{record}/edit'),
        ];
    }

    public static function etiquetaItemRepeater(array $state): ?HtmlString
    {
        $nombre = null;
        $thumbUrl = null;

        if (! empty($state['mobiliario_id'])) {
            $mobiliario = Mobiliario::find($state['mobiliario_id']);
            $nombre = $mobiliario?->nombre;
            $thumbUrl = static::urlMiniaturaMedia($mobiliario?->getFirstMedia('imagenes'));
        } elseif (! empty($state['insumo_id'])) {
            $insumo = Insumo::find($state['insumo_id']);
            $nombre = $insumo?->nombre ? '[SILLA] '.$insumo->nombre : null;
            $thumbUrl = static::urlMiniaturaMedia($insumo?->getFirstMedia('imagen'));
        }

        if (! $nombre) {
            return null;
        }

        $sector = ! empty($state['sector_id'])
            ? (Sector::find($state['sector_id'])?->nombre ?: 'Sin sector')
            : 'Sin sector';

        $cantidad = max(1, (int) ($state['cantidad'] ?? 1));

        return static::htmlEtiquetaItemRepeater($nombre, $sector, $cantidad, $thumbUrl);
    }

    public static function htmlEtiquetaItemRepeater(string $nombre, string $sector, int $cantidad, ?string $thumbUrl): HtmlString
    {
        $texto = e($nombre).' · '.e($sector).' ('.$cantidad.')';

        if (! filled($thumbUrl)) {
            return new HtmlString($texto);
        }

        $img = '<img src="'.e($thumbUrl).'" alt=""'
            .' style="width:40px;height:40px;object-fit:contain;flex-shrink:0;border-radius:0.25rem;background:#f3f4f6;"'
            .'>';

        return new HtmlString(
            '<span class="inline-flex items-center gap-2 min-w-0">'.$img.'<span>'.$texto.'</span></span>'
        );
    }

    public static function urlMiniaturaMedia(?Media $media): ?string
    {
        if (! $media) {
            return null;
        }

        $url = $media->hasGeneratedConversion('thumb')
            ? $media->getUrl('thumb')
            : $media->getUrl();

        return filled($url) ? $url : null;
    }

}
