<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsumoResource\Pages;
use App\Models\Insumo;
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
                    ->searchable()->preload()->required(),
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
                Forms\Components\Toggle::make('activo')->default(true),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('unidadMedida.abreviatura')
                    ->label('Unidad')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('stock_minimo')
                    ->label('Stock mín.')->numeric(2),
                Tables\Columns\TextColumn::make('ubicacion')->label('Ubicación'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unidad_medida_id')
                    ->label('Unidad')
                    ->relationship('unidadMedida', 'nombre'),
                Tables\Filters\TernaryFilter::make('activo'),
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
            'index'  => Pages\ListInsumos::route('/'),
            'create' => Pages\CreateInsumo::route('/create'),
            'edit'   => Pages\EditInsumo::route('/{record}/edit'),
        ];
    }
}
