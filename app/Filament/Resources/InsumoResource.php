<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsumoResource\Pages;
use App\Filament\Resources\InsumoResource\RelationManagers;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use App\Models\CategoriaInsumo;
use App\Models\TipoSilla;
use App\Models\Marca;
use App\Models\Proveedor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InsumoResource extends BaseResource
{
    protected static ?string $model = Insumo::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $modelLabel = 'Insumo';
    protected static ?string $pluralModelLabel = 'Insumos';
    protected static ?int $navigationSort = 4;
    // protected static bool $shouldRegisterNavigation = false;

    public static function verImagenPageAction(\Closure $getRecord): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('ver_imagen')
            ->label('Ver imagen')
            ->icon('heroicon-o-photo')
            ->color('info')
            ->visible(fn (): bool => $getRecord()->tieneImagen())
            ->url(fn (): string => $getRecord()->imagenUrl())
            ->openUrlInNewTab();
    }

    public static function verPlanoPageAction(\Closure $getRecord): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('ver_plano')
            ->label('Ver plano')
            ->icon('heroicon-o-document-text')
            ->color('warning')
            ->visible(fn (): bool => $getRecord()->tienePlano())
            ->url(fn (): string => $getRecord()->planoUrl())
            ->openUrlInNewTab();
    }

    public static function verPlanoTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('verPlano')
            ->label('Ver plano')
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->url(fn (Insumo $record): string => $record->planoUrl())
            ->openUrlInNewTab()
            ->visible(fn (Insumo $record): bool => $record->tienePlano());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create')
                    ->placeholder('Se asigna automáticamente'),
                Forms\Components\TextInput::make('nombre')
                    ->required()->maxLength(255),
                Forms\Components\Select::make('unidad_medida_id')
                    ->label('Unidad de medida')
                    ->relationship('unidadMedida', 'nombre')
                    ->searchable()->preload()->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()->maxLength(255),
                        Forms\Components\TextInput::make('abreviatura')
                            ->label('Abreviatura')
                            ->required()->maxLength(20),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa')
                            ->default(true),
                    ])
                    ->createOptionUsing(fn (array $data) => UnidadMedida::create($data)->getKey())
                    ->editOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()->maxLength(255),
                        Forms\Components\TextInput::make('abreviatura')
                            ->label('Abreviatura')
                            ->required()->maxLength(20),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa'),
                    ])
                    ->getSelectedRecordUsing(fn ($state): ?UnidadMedida => UnidadMedida::find($state))
                    ->fillEditOptionActionFormUsing(fn ($component): array =>
                        $component->getSelectedRecord()?->only('nombre', 'abreviatura', 'activo') ?? []
                    )
                    ->updateOptionUsing(function (array $data, $form): void {
                        $form->getRecord()?->update($data);
                    }),
                Forms\Components\TextInput::make('stock_actual')
                    ->label('Stock actual')
                    ->numeric()->minValue(0)->default(0),
                Forms\Components\TextInput::make('stock_minimo')
                    ->label('Stock mínimo')
                    ->numeric()->minValue(0)->default(0),
                Forms\Components\TextInput::make('precio_costo')
                    ->label('Precio de costo')
                    ->numeric()->minValue(0)->prefix('$')->nullable(),
                Forms\Components\TextInput::make('ubicacion')
                    ->label('Ubicación')->maxLength(255),
                Forms\Components\Select::make('categoriasInsumo')
                    ->label('Categoría')
                    ->relationship('categoriasInsumo', 'nombre')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()->maxLength(255),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activa')
                            ->default(true),
                    ]),
                Forms\Components\Toggle::make('activo')->default(true),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Datos de Silla')
                ->schema([
                    Forms\Components\Select::make('proveedor_id')
                        ->label('Proveedor')
                        ->relationship('proveedor', 'razon_social', fn ($query) => $query
                            ->where('activo', true)
                            ->whereHas('rubros', fn ($q) => $q->whereRaw("LOWER(nombre) LIKE '%silla%'"))
                            ->orderBy('razon_social'))
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Forms\Components\Select::make('tipo_silla_id')
                        ->label('Tipo de Silla')
                        ->relationship('tipoSilla', 'nombre')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nombre')
                                ->label('Nombre')
                                ->required()->maxLength(255),
                            Forms\Components\Toggle::make('activo')
                                ->label('Activo')
                                ->default(true),
                        ])
                        ->createOptionUsing(fn (array $data) => TipoSilla::create($data)->getKey()),

                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('marcasSilla')
                        ->relationship('marcasSilla')
                        ->label('Marcas y nombre de fantasía')
                        ->schema([
                            Forms\Components\Select::make('marca_id')
                                ->label('Marca')
                                ->options(Marca::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('nombre_fantasia')
                                ->label('Nombre de fantasía')
                                ->maxLength(255)
                                ->nullable(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Agregar marca')
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get): bool => CategoriaInsumo::whereIn('id', (array) ($get('categoriasInsumo') ?? []))
                    ->whereRaw("LOWER(nombre) LIKE '%silla%'")
                    ->exists()
                ),

            Forms\Components\Section::make('Plantilla de flujo externo')
                ->schema([
                    Forms\Components\Placeholder::make('')
                        ->content('La plantilla de flujo externo podrá ser configurada una vez guardado el insumo.'),
                ])
                ->hiddenOn('edit'),

            Forms\Components\Section::make('Imagen y Plano')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('imagen')
                    ->label('Imagen')
                    ->collection('imagen')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([null, '1:1', '4:3', '16:9'])
                    ->helperText('Imagen de referencia del insumo. No requerida.')
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

                Forms\Components\SpatieMediaLibraryFileUpload::make('plano')
                    ->label('Plano (PDF)')
                    ->collection('plano')
                    ->acceptedFileTypes(['application/pdf'])
                    ->helperText('Plano técnico en formato PDF. No requerido.')
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
            ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Datos del insumo')->schema([
                Infolists\Components\TextEntry::make('codigo')
                    ->label('Código')
                    ->badge()
                    ->color('primary'),

                Infolists\Components\TextEntry::make('nombre')
                    ->label('Nombre'),

                Infolists\Components\TextEntry::make('categoriasInsumo.nombre')
                    ->label('Categorías')
                    ->badge()
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('unidadMedida.nombre')
                    ->label('Unidad de medida')
                    ->placeholder('—'),

                Infolists\Components\IconEntry::make('activo')
                    ->label('Activo')
                    ->boolean(),

                Infolists\Components\TextEntry::make('ubicacion')
                    ->label('Ubicación')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('precio_costo')
                    ->label('Precio de costo')
                    ->money('ARS')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('observaciones')
                    ->placeholder('—')
                    ->columnSpanFull(),
            ])->columns(3),

            Infolists\Components\Section::make('Stock')->schema([
                Infolists\Components\TextEntry::make('stock_actual')
                    ->label('Stock actual')
                    ->numeric(2),

                Infolists\Components\TextEntry::make('stock_minimo')
                    ->label('Stock mínimo')
                    ->numeric(2),

                Infolists\Components\TextEntry::make('stock_comprometido')
                    ->label('Cantidad comprometida')
                    ->numeric(2),

                Infolists\Components\TextEntry::make('pendiente_recepcion')
                    ->label('Cantidad en compra')
                    ->numeric(2),

                Infolists\Components\TextEntry::make('stock_proyectado')
                    ->label('Stock proyectado')
                    ->numeric(2)
                    ->color(fn (Insumo $record): string => $record->stock_proyectado < 0 ? 'danger' : 'success'),

                Infolists\Components\TextEntry::make('es_critico')
                    ->label('Estado de stock')
                    ->badge()
                    ->getStateUsing(fn (Insumo $record): string => $record->es_critico ? 'Crítico' : 'Normal')
                    ->color(fn (Insumo $record): string => $record->es_critico ? 'danger' : 'success'),
            ])->columns(3),

            Infolists\Components\Section::make('Datos de silla')
                ->schema([
                    Infolists\Components\TextEntry::make('proveedor.razon_social')
                        ->label('Proveedor')
                        ->placeholder('—'),

                    Infolists\Components\TextEntry::make('tipoSilla.nombre')
                        ->label('Tipo de silla')
                        ->placeholder('—'),

                    Infolists\Components\TextEntry::make('descripcion')
                        ->label('Descripción')
                        ->placeholder('—')
                        ->columnSpanFull(),

                    Infolists\Components\TextEntry::make('marcas_silla_resumen')
                        ->label('Marcas y nombre de fantasía')
                        ->getStateUsing(function (Insumo $record): string {
                            $record->loadMissing('marcasSilla.marca');

                            if ($record->marcasSilla->isEmpty()) {
                                return '—';
                            }

                            return $record->marcasSilla
                                ->map(fn ($item): string => trim(
                                    ($item->marca?->nombre ?? 'Marca') .
                                    ($item->nombre_fantasia ? ': ' . $item->nombre_fantasia : '')
                                ))
                                ->implode(' · ');
                        })
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->visible(fn (Insumo $record): bool => $record->esSilla()),

            Infolists\Components\Section::make('Imagen y plano')
                ->schema([
                    Infolists\Components\ViewEntry::make('media_preview')
                        ->label('')
                        ->view('filament.insumo.media-preview'),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('imagen')
                    ->collection('imagen')
                    ->conversion('thumb')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit:contain; background:#f3f4f6;']),
                Tables\Columns\TextColumn::make('codigo')
                    ->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('categoriasInsumo.nombre')
                    ->label('Categoría')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_actual')
                    ->label('Stock actual')
                    ->tooltip('Stock físico disponible en depósito.')
                    ->numeric(0),
                Tables\Columns\TextColumn::make('stock_comprometido')
                    ->label('Cantidad Comprometida')
                    ->tooltip('Suma de reservas activas de presupuestos vigentes.')
                    ->numeric(0),
                Tables\Columns\TextColumn::make('pendiente_recepcion')
                    ->label('Cantidad en Compra')
                    ->tooltip('Lotes en proceso más órdenes de compra pendientes de recepción.')
                    ->numeric(0),
                Tables\Columns\TextColumn::make('stock_proyectado')
                    ->label('Stock proyectado')
                    ->tooltip('Stock actual + cantidad en compra - cantidad comprometida.')
                    ->color(fn ($state): string => $state < 0 ? 'danger' : 'success')
                    ->numeric(0),
                Tables\Columns\TextColumn::make('precio_costo')
                    ->label('Ultimo Precio')->numeric(0),
                Tables\Columns\TextColumn::make('ubicacion')->label('Ubicación'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unidad_medida_id')
                    ->label('Unidad')
                    ->relationship('unidadMedida', 'nombre'),
                Tables\Filters\TernaryFilter::make('activo'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                static::verPlanoTableAction(),

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
            RelationManagers\PlantillaFlujosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInsumos::route('/'),
            'create' => Pages\CreateInsumo::route('/create'),
            'view'   => Pages\ViewInsumo::route('/{record}'),
            'edit'   => Pages\EditInsumo::route('/{record}/edit'),
        ];
    }
}
