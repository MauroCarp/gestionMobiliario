<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsumoResource\Pages;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InsumoResource extends Resource
{
    protected static ?string $model = Insumo::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $modelLabel = 'Insumo';
    protected static ?string $pluralModelLabel = 'Insumos';
    protected static ?int $navigationSort = 4;
    // protected static bool $shouldRegisterNavigation = false;


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
                Forms\Components\TextInput::make('stock_minimo')
                    ->label('Stock mínimo')
                    ->numeric()->minValue(0)->default(0),
                Forms\Components\TextInput::make('stock_actual')
                    ->label('Stock actual')
                    ->numeric()->minValue(0)->default(0),
                Forms\Components\TextInput::make('precio_costo')
                    ->label('Precio de costo')
                    ->numeric()->minValue(0)->prefix('$')->nullable(),
                Forms\Components\TextInput::make('ubicacion')
                    ->label('Ubicación')->maxLength(255),
                Forms\Components\TagsInput::make('tag')
                    ->label('Etiquetas')
                    ->placeholder('Ej: Patas, Tornillos...'),
                Forms\Components\Toggle::make('activo')->default(true),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),

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
                Tables\Columns\TextColumn::make('tag')
                    ->label('Etiquetas')
                    ->badge()
                    ->separator(',')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('stock_actual')
                    ->label('Stock actual')->numeric(2),
                Tables\Columns\TextColumn::make('ubicacion')->label('Ubicación'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unidad_medida_id')
                    ->label('Unidad')
                    ->relationship('unidadMedida', 'nombre'),
                Tables\Filters\TernaryFilter::make('activo'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('verPlano')
                    ->label('Ver plano')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn ($record) => $record->getFirstMediaUrl('plano'))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->getFirstMedia('plano') !== null),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInsumos::route('/'),
            'create' => Pages\CreateInsumo::route('/create'),
            'edit'   => Pages\EditInsumo::route('/{record}/edit'),
        ];
    }
}
