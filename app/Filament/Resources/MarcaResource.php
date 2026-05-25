<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarcaResource\Pages;
use App\Models\Marca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarcaResource extends Resource
{
    protected static ?string $model = Marca::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel = 'Marca';
    protected static ?string $pluralModelLabel = 'Marcas';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información de la Marca')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),

                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('marcas/logos')
                    ->disk('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth(400)
                    ->imageResizeTargetHeight(225)
                    ->helperText('Podés editar la imagen antes de guardar. Se ajustará sin recortar.')
                    ->columnSpan(2),

                Forms\Components\Toggle::make('activo')
                    ->default(true)
                    ->label('Activa'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->circular()
                    ->disk('public'),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agencias_count')
                    ->label('Agencias')
                    ->counts('agencias'),
                Tables\Columns\TextColumn::make('proyectos_count')
                    ->label('Proyectos')
                    ->counts('proyectos'),
                Tables\Columns\IconColumn::make('activo')
                    ->boolean()
                    ->label('Activa'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activo')->label('Estado'),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMarcas::route('/'),
            'create' => Pages\CreateMarca::route('/create'),
            'edit'   => Pages\EditMarca::route('/{record}/edit'),
        ];
    }
}
