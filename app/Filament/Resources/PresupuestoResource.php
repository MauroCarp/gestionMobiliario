<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresupuestoResource\Pages;
use App\Filament\Resources\PresupuestoResource\RelationManagers;
use App\Models\Agencia;
use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\Presupuesto;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresupuestoResource extends Resource
{
    protected static ?string $model = Presupuesto::class;
    protected static ?string $navigationIcon   = 'heroicon-o-document-text';
    protected static ?string $navigationGroup  = 'Operaciones';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $modelLabel       = 'Presupuesto';
    protected static ?string $pluralModelLabel = 'Presupuestos';

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
                        ->columnSpan(2),

                    Forms\Components\Select::make('responsable_id')
                        ->label('Responsable')
                        ->relationship('responsable', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(fn () => auth()->id())
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('fecha_emision')
                        ->label('Fecha de Emisión')
                        ->required()
                        ->default(now())
                        ->columnSpan(1),
                ])
                ->columns(3),

            Forms\Components\Section::make('Items del Presupuesto')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Forms\Components\Select::make('_item_selector')
                                ->label('Mobiliario / Silla')
                                ->options(function (Get $get) {
                                    $agenciaId = $get('../../agencia_id');

                                    // Mobiliarios: del proyecto si hay agencia, o todos activos
                                    if ($agenciaId) {
                                        $agencia = Agencia::find($agenciaId);
                                        $proyecto = $agencia?->proyecto;
                                        if ($proyecto) {
                                            $mobiliarios = $proyecto->mobiliarios()
                                                ->where('estado', 'activo')
                                                ->orderBy('nombre')
                                                ->get()
                                                ->mapWithKeys(fn ($m) => [
                                                    'mob_' . $m->id => "[{$m->codigo_interno}] {$m->nombre}",
                                                ])
                                                ->toArray();
                                        }
                                    }

                                    if (!isset($mobiliarios)) {
                                        $mobiliarios = \App\Models\Mobiliario::where('estado', 'activo')
                                            ->orderBy('nombre')
                                            ->get()
                                            ->mapWithKeys(fn ($m) => [
                                                'mob_' . $m->id => "[{$m->codigo_interno}] {$m->nombre}",
                                            ])
                                            ->toArray();
                                    }

                                    // Insumos de categoría Sillas
                                    $sillas = Insumo::whereHas('categoriasInsumo', fn ($q) =>
                                            $q->where('nombre', 'like', '%silla%')
                                        )
                                        ->where('activo', true)
                                        ->orderBy('nombre')
                                        ->get()
                                        ->mapWithKeys(fn ($i) => [
                                            'ins_' . $i->id => "[SILLA] [{$i->codigo}] {$i->nombre}",
                                        ])
                                        ->toArray();

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
                                ->columnSpan(1),

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
                        ->orderColumn('orden')
                        ->addActionLabel('+ Agregar mobiliario')
                        ->defaultItems(0)
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => match (true) {
                            !empty($state['mobiliario_id']) => \App\Models\Mobiliario::find($state['mobiliario_id'])?->nombre,
                            !empty($state['insumo_id'])     => ('[SILLA] ' . (Insumo::find($state['insumo_id'])?->nombre ?? '')),
                            default                         => null,
                        })
                        ->collapsible(),
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
                    ->label('Fecha de vencimiento')
                    ->date('d/m/Y')
                    ->placeholder('—'),
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

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // ── Botones de estado visibles directamente en la fila ──────
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->visible(fn (Presupuesto $record): bool => $record->puedeAprobar())
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
                    ->visible(fn (Presupuesto $record): bool => $record->estado === 'confirmado')
                    ->requiresConfirmation()
                    ->modalHeading('Registrar pago')
                    ->modalDescription('Se registrará el pago del presupuesto. El stock se descuenta al confirmar la finalización de cada ítem.')
                    ->action(function (Presupuesto $record): void {
                        $record->cambiarEstado('pagado');
                        Notification::make()->success()->title('Presupuesto marcado como pagado.')->send();
                    }),

                Tables\Actions\ViewAction::make()
                    ->button(),

                // ── Resto de acciones en el menú desplegable ────────────────
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('pdf')
                        ->label('Exportar PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger')
                        ->url(fn (Presupuesto $record) => route('presupuesto.pdf.viewer', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('excel')
                        ->label('Exportar Excel')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->url(fn (Presupuesto $record) => route('presupuesto.excel', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('produccionExcel')
                        ->label('Exportar Excel Producción')
                        ->icon('heroicon-o-table-cells')
                        ->color('info')
                        ->visible(fn (Presupuesto $record): bool => in_array($record->estado, ['aprobado', 'confirmado', 'pagado']))
                        ->url(fn (Presupuesto $record) => route('presupuesto.produccion.excel', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('enviarRevision')
                        ->label('Enviar a Revisión')
                        ->icon('heroicon-o-arrow-right-circle')
                        ->color('warning')
                        ->visible(fn (Presupuesto $record): bool => $record->puedeEnviarARevision())
                        ->requiresConfirmation()
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('en_revision');
                            Notification::make()->success()->title('Enviado a revisión')->send();
                        }),

                    Tables\Actions\Action::make('rechazar')
                        ->label('Rechazar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Presupuesto $record): bool => $record->puedeRechazar())
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
                        ->visible(fn (Presupuesto $record): bool => $record->puedeCancelar())
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
                            in_array($record->estado, ['confirmado', 'rechazado'])
                        )
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

                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
