<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobiliarioResource\Pages;
use App\Filament\Resources\MobiliarioResource\RelationManagers;
use App\Models\Insumo;
use App\Models\Mobiliario;
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
                    ->searchable()->preload()->required(),
                Forms\Components\Select::make('estado')
                    ->options(Mobiliario::ESTADOS)
                    ->default('activo')->required(),
                Forms\Components\Textarea::make('descripcion')
                    ->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)->columnSpanFull(),
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
                            ->options(
                                fn () => Insumo::where('activo', true)
                                    ->orderBy('nombre')
                                    ->get()
                                    ->mapWithKeys(fn ($i) => [$i->id => $i->nombre])
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->columnSpan(2),

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
                Tables\Columns\BadgeColumn::make('estado')
                    ->formatStateUsing(fn ($state) => Mobiliario::ESTADOS[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'activo'        => 'success',
                        'inactivo'      => 'warning',
                        'discontinuado' => 'danger',
                        default         => 'gray',
                    }),
                Tables\Columns\TextColumn::make('version_actual')
                    ->label('Versión')->badge()->color('info'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y')->sortable()->label('Modificado'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre'),
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
            ->withoutGlobalScopes([SoftDeletingScope::class]);
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
