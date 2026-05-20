<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnidadMedidaResource\Pages;
use App\Models\UnidadMedida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnidadMedidaResource extends Resource
{
    protected static ?string $model = UnidadMedida::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $modelLabel = 'Unidad de medida';
    protected static ?string $pluralModelLabel = 'Unidades de medida';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required()->maxLength(100),
            Forms\Components\TextInput::make('abreviatura')->required()->maxLength(20)->unique(ignoreRecord: true),
            Forms\Components\Toggle::make('activo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('abreviatura')->badge(),
                Tables\Columns\TextColumn::make('insumos_count')->label('Insumos')->counts('insumos'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUnidadesMedida::route('/'),
            'create' => Pages\CreateUnidadMedida::route('/create'),
            'edit'   => Pages\EditUnidadMedida::route('/{record}/edit'),
        ];
    }
}
