<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobiliarioResource\Pages;
use App\Filament\Resources\MobiliarioResource\RelationManagers;
use App\Models\CategoriaMobiliario;
use App\Models\Insumo;
use App\Models\Marca;
use App\Models\Mobiliario;
use App\Models\UnidadMedida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MobiliarioResource extends Resource
{
    protected static ?string $model = Mobiliario::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $modelLabel = 'Mobiliario';
    protected static ?string $pluralModelLabel = 'Mobiliarios';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información principal')->schema([
                Forms\Components\TextInput::make('codigo_interno')
                    ->required()->maxLength(50)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nombre')
                    ->required()->maxLength(255),
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre')
                    ->searchable()->preload()->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()->maxLength(255),
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(2),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa')
                            ->default(true),
                    ])
                    ->createOptionUsing(fn (array $data) => CategoriaMobiliario::create($data)->getKey())
                    ->editOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()->maxLength(255),
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(2),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa'),
                    ])
                    ->getSelectedRecordUsing(fn ($state): ?CategoriaMobiliario => CategoriaMobiliario::find($state))
                    ->fillEditOptionActionFormUsing(fn ($component): array => $component->getSelectedRecord()?->only('nombre', 'descripcion', 'activo') ?? [])
                    ->updateOptionUsing(function (array $data, $form): void {
                        $form->getRecord()?->update($data);
                    }),
                Forms\Components\Select::make('marca_id')
                    ->label('Marca')
                    ->relationship('marca', 'nombre', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('activo', true)->orderBy('nombre'))
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('Sin marca específica')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->required()->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('marcas/logos')
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth(400)
                            ->imageResizeTargetHeight(225)
                            ->helperText('Opcional'),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa')
                            ->default(true),
                    ])
                    ->createOptionUsing(fn (array $data) => Marca::create($data)->getKey())
                    ->editOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->required()->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('marcas/logos')
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth(400)
                            ->imageResizeTargetHeight(225)
                            ->helperText('Opcional')
                            ->getUploadedFileUsing(function ($component, string $file): ?array {
                                $storage = $component->getDisk();
                                if (! $storage->exists($file)) {
                                    return null;
                                }
                                $mimeType = $storage->mimeType($file);
                                $content  = $storage->get($file);
                                if ($content === false || $content === null) {
                                    return null;
                                }
                                return [
                                    'name' => basename($file),
                                    'size' => $storage->size($file),
                                    'type' => $mimeType,
                                    'url'  => 'data:' . $mimeType . ';base64,' . base64_encode($content),
                                ];
                            }),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa'),
                    ])
                    ->getSelectedRecordUsing(fn ($state): ?Marca => Marca::find($state))
                    ->fillEditOptionActionFormUsing(fn ($component): array =>
                        $component->getSelectedRecord()?->only('nombre', 'logo', 'activo') ?? []
                    )
                    ->updateOptionUsing(function (array $data, $form): void {
                        $form->getRecord()?->update($data);
                    }),
                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3),
                Forms\Components\Textarea::make('observaciones')
                    ->label('Descripción')
                    ->rows(3),
            ])->columns(2),

            Forms\Components\Section::make('Imagen principal')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('imagenes')
                    ->collection('imagenes')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,    // libre
                        '4:3',
                        '3:4',
                        '1:1',
                        '16:9',
                    ])
                    ->helperText('Podés editar/recortar la imagen antes de guardar. Solo se permite una imagen.')
                    ->getUploadedFileUsing(function ($component, string $file): ?array {
                        if (!$component->getRecord()) {
                            return null;
                        }
                        $media = $component->getRecord()->getRelationValue('media')->firstWhere('uuid', $file);
                        if (!$media) {
                            return null;
                        }
                        $mimeType = $media->getAttributeValue('mime_type');
                        $filePath = $media->getPath();
                        if (!file_exists($filePath)) {
                            return null;
                        }
                        $content = file_get_contents($filePath);
                        if ($content === false) {
                            return null;
                        }
                        return [
                            'name' => $media->getAttributeValue('name') ?? $media->getAttributeValue('file_name'),
                            'size' => $media->getAttributeValue('size'),
                            'type' => $mimeType,
                            'url'  => 'data:' . $mimeType . ';base64,' . base64_encode($content),
                        ];
                    }),
            ]),

            Forms\Components\Section::make('Atributos configurables')->schema([
                Forms\Components\Repeater::make('atributos')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('clave')
                            ->required()->label('Atributo')->placeholder('ej: Alto, Ancho...'),
                        Forms\Components\TextInput::make('valor')
                            ->required()->label('Valor')->placeholder('ej: 120cm'),
                    ])
                    ->columns(2)
                    ->addActionLabel('Agregar atributo'),
            ]),

            Forms\Components\Section::make('Composición técnica (Insumos)')->schema([
                Forms\Components\Repeater::make('composicionTecnica')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('insumo_id')
                            ->label('Insumo')
                            ->relationship(
                                'insumo',
                                'nombre',
                                fn ($query) => $query->where('activo', true)->orderBy('nombre'),
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->columnSpan(2)
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()->maxLength(255),
                                Forms\Components\Select::make('unidad_medida_id')
                                    ->label('Unidad de Medida')
                                    ->options(fn () => UnidadMedida::orderBy('nombre')->pluck('nombre', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('precio_costo')
                                    ->label('Precio de Costo')
                                    ->numeric()->minValue(0)->step(0.01)->prefix('$'),
                                Forms\Components\TextInput::make('stock_minimo')
                                    ->label('Stock Mínimo')
                                    ->numeric()->minValue(0)->step(0.01),
                                Forms\Components\Toggle::make('activo')
                                    ->label('Activo')
                                    ->default(true),
                            ])
                            ->createOptionUsing(fn (array $data) => Insumo::create($data)->getKey())
                            ->editOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()->maxLength(255),
                                Forms\Components\Select::make('unidad_medida_id')
                                    ->label('Unidad de Medida')
                                    ->options(fn () => UnidadMedida::orderBy('nombre')->pluck('nombre', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('precio_costo')
                                    ->label('Precio de Costo')
                                    ->numeric()->minValue(0)->step(0.01)->prefix('$'),
                                Forms\Components\TextInput::make('stock_minimo')
                                    ->label('Stock Mínimo')
                                    ->numeric()->minValue(0)->step(0.01),
                                Forms\Components\Toggle::make('activo')
                                    ->label('Activo'),
                            ])
                            ->getSelectedRecordUsing(fn ($state): ?Insumo => Insumo::find($state))
                            ->fillEditOptionActionFormUsing(fn ($component): array =>
                                $component->getSelectedRecord()?->only('nombre', 'unidad_medida_id', 'precio_costo', 'stock_minimo', 'activo') ?? []
                            )
                            ->updateOptionUsing(function (array $data, $form): void {
                                $form->getRecord()?->update($data);
                            }),

                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\Placeholder::make('unidad')
                            ->label('Unidad de Medida')
                            ->content(function (Forms\Get $get): string {
                                $id = $get('insumo_id');
                                if (! $id) return '—';
                                return Insumo::with('unidadMedida')
                                    ->find($id)?->unidadMedida?->nombre ?? '—';
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->addActionLabel('Agregar insumo')
                    ->defaultItems(0)
                    ->reorderable()
                    ->collapsible(),
            ]),

            Forms\Components\Section::make('Documentos técnicos')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('documentos')
                    ->collection('documentos')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('imagenes')
                    ->collection('imagenes')
                    ->conversion('thumb')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit:contain; background:#f3f4f6;']),
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('atributos_resumen')
                    ->label('Atributos')
                    ->getStateUsing(fn ($record) =>
                        $record->atributos->isNotEmpty()
                            ? $record->atributos->map(fn ($a) => $a->clave . ': ' . $a->valor)->join('  ·  ')
                            : '—'
                    )
                    ->wrap()
                    ->searchable(false)
                    ->sortable(false),
                // Tables\Columns\BadgeColumn::make('estado')
                //     ->formatStateUsing(fn ($state) => Mobiliario::ESTADOS[$state] ?? $state)
                //     ->color(fn ($state) => match ($state) {
                //         'activo'        => 'success',
                //         'inactivo'      => 'warning',
                //         'discontinuado' => 'danger',
                //         default         => 'gray',
                //     }),
                // Tables\Columns\TextColumn::make('version_actual')
                //     ->label('Versión')->badge()->color('info'),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime('d/m/Y')->sortable()->label('Modificado'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre'),
                Tables\Filters\SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->relationship('marca', 'nombre')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('estado')
                    ->options(Mobiliario::ESTADOS),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\ComposicionTecnicaRelationManager::class,
            RelationManagers\HistorialVersionesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->with('atributos');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMobiliarios::route('/'),
            'create' => Pages\CreateMobiliario::route('/create'),
            'edit'   => Pages\EditMobiliario::route('/{record}/edit'),
        ];
    }
}
