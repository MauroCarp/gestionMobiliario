<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoProcesoExternoResource\Pages;
use App\Models\TipoProcesoExterno;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TipoProcesoExternoResource extends Resource
{
    protected static ?string $model = TipoProcesoExterno::class;
    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    // protected static ?string $navigationGroup = 'Tercerizados';
    protected static ?string $modelLabel      = 'Tipo de proceso';
    protected static ?string $pluralModelLabel = 'Tipos de proceso';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('descripcion')
                ->label('Descripción')
                ->maxLength(255),
            Forms\Components\Select::make('color')
                ->options([
                    'gray'    => 'Gris',
                    'info'    => 'Azul',
                    'success' => 'Verde',
                    'warning' => 'Naranja',
                    'danger'  => 'Rojo',
                    'primary' => 'Primario',
                ])
                ->default('gray')
                ->required(),
            Forms\Components\Toggle::make('activo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->badge()
                    ->color(fn ($record) => $record->color)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTiposProcesoExterno::route('/'),
            'create' => Pages\CreateTipoProcesoExterno::route('/create'),
            'edit'   => Pages\EditTipoProcesoExterno::route('/{record}/edit'),
        ];
    }
}
