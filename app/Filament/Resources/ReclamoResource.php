<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReclamoResource\Pages;
use App\Filament\Resources\ReclamoResource\RelationManagers;
use App\Models\Agencia;
use App\Models\Presupuesto;
use App\Models\Reclamo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReclamoResource extends BaseResource
{
    protected static ?string $model = Reclamo::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Operaciones';

    protected static ?string $modelLabel = 'Reclamo';

    protected static ?string $pluralModelLabel = 'Reclamos';

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canView(Model $record): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->check();
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check();
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check();
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    /**
     * @return array<int, string>
     */
    public static function opcionesAgencia(): array
    {
        return Agencia::with('proyecto.marca')
            ->orderBy('nombre')
            ->get()
            ->mapWithKeys(fn (Agencia $agencia): array => [
                $agencia->id => $agencia->nombre.' — '.($agencia->marca?->nombre ?? '—'),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function opcionesPresupuesto(?int $agenciaId): array
    {
        if (! $agenciaId) {
            return [];
        }

        return Presupuesto::query()
            ->where('agencia_id', $agenciaId)
            ->orderByDesc('codigo')
            ->get()
            ->mapWithKeys(fn (Presupuesto $presupuesto): array => [
                $presupuesto->id => $presupuesto->codigo.' — '.(Presupuesto::ESTADOS[$presupuesto->estado] ?? $presupuesto->estado),
            ])
            ->all();
    }

    public static function campoImagenesRegistro(): Forms\Components\SpatieMediaLibraryFileUpload
    {
        return Forms\Components\SpatieMediaLibraryFileUpload::make('imagenes')
            ->label('Imágenes y videos')
            ->collection('imagenes')
            ->multiple()
            ->reorderable()
            ->appendFiles()
            ->downloadable()
            ->openable()
            ->acceptedFileTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'video/mp4',
                'video/webm',
            ])
            ->maxSize(51200)
            ->helperText('Fotos o videos de lo sucedido. Podés cargar más de uno (hasta 50 MB por archivo).')
            ->columnSpanFull();
    }

    /**
     * @param  callable(Get): ?int  $presupuestoId
     * @return array<int, Forms\Components\Component>
     */
    public static function camposSelectorItem(callable $presupuestoId): array
    {
        return [
            Forms\Components\Select::make('_item_selector')
                ->label('Mobiliario / Silla')
                ->options(fn (Get $get): array => Reclamo::opcionesItemParaPresupuesto($presupuestoId($get)))
                ->searchable()
                ->required()
                ->live()
                ->dehydrated(false)
                ->disabled(fn (Get $get): bool => ! $presupuestoId($get))
                ->helperText(fn (Get $get): ?string => $presupuestoId($get)
                    ? null
                    : 'Elegí un presupuesto para listar los mobiliarios y sillas.')
                ->formatStateUsing(function ($state, $record): ?string {
                    if ($record?->mobiliario_id) {
                        return 'mob_'.$record->mobiliario_id;
                    }

                    if ($record?->insumo_id) {
                        return 'ins_'.$record->insumo_id;
                    }

                    return $state;
                })
                ->afterStateUpdated(function ($state, Set $set): void {
                    if (! $state) {
                        $set('mobiliario_id', null);
                        $set('insumo_id', null);

                        return;
                    }

                    if (str_starts_with((string) $state, 'mob_')) {
                        $set('mobiliario_id', (int) substr((string) $state, 4));
                        $set('insumo_id', null);

                        return;
                    }

                    if (str_starts_with((string) $state, 'ins_')) {
                        $set('insumo_id', (int) substr((string) $state, 4));
                        $set('mobiliario_id', null);
                    }
                }),
            Forms\Components\Hidden::make('mobiliario_id'),
            Forms\Components\Hidden::make('insumo_id'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del reclamo')->schema([
                Forms\Components\Select::make('agencia_id')
                    ->label('Agencia')
                    ->options(fn (): array => static::opcionesAgencia())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('presupuesto_id', null))
                    ->disabled(fn (?Reclamo $record): bool => $record?->exists && ! $record->puedeEditarCabecera())
                    ->helperText(fn (?Reclamo $record): ?string => $record?->exists && ! $record->puedeEditarCabecera()
                        ? 'No se puede cambiar la agencia ni el presupuesto porque el reclamo ya tiene registros.'
                        : null),
                Forms\Components\Select::make('presupuesto_id')
                    ->label('Presupuesto')
                    ->options(fn (Get $get): array => static::opcionesPresupuesto($get('agencia_id') ? (int) $get('agencia_id') : null))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->disabled(fn (Get $get, ?Reclamo $record): bool => ! $get('agencia_id')
                        || ($record?->exists && ! $record->puedeEditarCabecera()))
                    ->rule(function (Get $get) {
                        return function (string $attribute, mixed $value, \Closure $fail) use ($get): void {
                            if (! $value) {
                                return;
                            }

                            $agenciaId = $get('agencia_id');

                            if (! Presupuesto::whereKey($value)->where('agencia_id', $agenciaId)->exists()) {
                                $fail('El presupuesto no pertenece a la agencia seleccionada.');
                            }
                        };
                    })
                    ->helperText('Se listan los presupuestos de la agencia seleccionada.'),
            ])->columns(2),

            Forms\Components\Section::make('Registros')
                ->description('Cargá uno o más reclamos. La fecha toma el día de hoy; se puede cambiar.')
                ->schema([
                    Forms\Components\Repeater::make('registros')
                        ->relationship()
                        ->schema([
                            ...static::camposSelectorItem(fn (Get $get): ?int => $get('../../presupuesto_id')
                                ? (int) $get('../../presupuesto_id')
                                : null),
                            Forms\Components\TextInput::make('observacion')
                                ->label('Observación')
                                ->maxLength(65535),
                            Forms\Components\DatePicker::make('fecha')
                                ->label('Fecha')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->default(fn () => now())
                                ->dehydrated(),
                            static::campoImagenesRegistro(),
                        ])
                        ->columns(3)
                        ->defaultItems(1)
                        ->addActionLabel('Agregar registro')
                        ->reorderable(false)
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            if (blank($data['fecha'] ?? null)) {
                                $data['fecha'] = now()->toDateString();
                            }

                            return $data;
                        })
                        ->columnSpanFull(),
                ])
                ->visibleOn('create'),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Datos del reclamo')->schema([
                Infolists\Components\TextEntry::make('agencia.nombre')
                    ->label('Agencia'),
                Infolists\Components\TextEntry::make('agencia.proyecto.marca.nombre')
                    ->label('Marca')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('presupuesto.codigo')
                    ->label('Presupuesto')
                    ->badge()
                    ->color('primary'),
                Infolists\Components\TextEntry::make('presupuesto.estado')
                    ->label('Estado del presupuesto')
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? (Presupuesto::ESTADOS[$state] ?? $state)
                        : '—'),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with([
                    'agencia.proyecto.marca',
                    'agencia.ciudad',
                    'presupuesto',
                    'registros.mobiliario',
                    'registros.insumo.marcasSilla',
                ])
                ->withCount([
                    'registros as pendientes_count' => fn (Builder $q) => $q->where('estado', 'pendiente'),
                    'registros as resueltos_count' => fn (Builder $q) => $q->where('estado', 'resuelto'),
                ]))
            ->columns([
                Tables\Columns\TextColumn::make('agencia.nombre')
                    ->label('Agencia')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agencia.ciudad.nombre')
                    ->label('Localidad')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('agencia.proyecto.marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('presupuesto.codigo')
                    ->label('Presupuesto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motivos')
                    ->label('Motivos')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(4)
                    ->expandableLimitedList()
                    ->wrap()
                    ->placeholder('—')
                    ->getStateUsing(fn (Reclamo $record): array => $record->registros
                        ->map(fn ($registro): string => $registro->etiquetaListado($record->agencia?->proyecto?->marca_id))
                        ->values()
                        ->all())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('registros', function (Builder $registros) use ($search): void {
                            $registros->where(function (Builder $motivo) use ($search): void {
                                $motivo
                                    ->where('observacion', 'like', "%{$search}%")
                                    ->orWhereHas('mobiliario', fn (Builder $mobiliario) => $mobiliario
                                        ->where('nombre', 'like', "%{$search}%")
                                        ->orWhere('codigo_interno', 'like', "%{$search}%"))
                                    ->orWhereHas('insumo', fn (Builder $insumo) => $insumo
                                        ->where('nombre', 'like', "%{$search}%")
                                        ->orWhereHas('marcasSilla', fn (Builder $marcaSilla) => $marcaSilla
                                            ->where('nombre_fantasia', 'like', "%{$search}%")));
                            });
                        });
                    }),
                Tables\Columns\TextColumn::make('pendientes_count')
                    ->label('Pendientes')
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('resueltos_count')
                    ->label('Resueltos')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (Reclamo $record): string => static::getUrl('view', ['record' => $record]))
            ->filters([
                Tables\Filters\SelectFilter::make('agencia_id')
                    ->label('Agencia')
                    ->options(fn (): array => static::opcionesAgencia())
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RegistrosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReclamos::route('/'),
            'create' => Pages\CreateReclamo::route('/create'),
            'view' => Pages\ViewReclamo::route('/{record}'),
            'edit' => Pages\EditReclamo::route('/{record}/edit'),
        ];
    }
}
