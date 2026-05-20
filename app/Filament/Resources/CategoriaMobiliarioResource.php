<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoriaMobiliarioResource\Pages;
use App\Models\CategoriaMobiliario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriaMobiliarioResource extends Resource
{
    protected static ?string $model = CategoriaMobiliario::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $modelLabel = 'Categoría';
    protected static ?string $pluralModelLabel = 'Categorías';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()->maxLength(255)->live(debounce: 500)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                        $set('slug', \Illuminate\Support\Str::slug($state))
                    ),
                Forms\Components\TextInput::make('slug')
                    ->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('icono')
                    ->placeholder('heroicon-o-squares-2x2')
                    ->helperText('Nombre del ícono Heroicons'),
                Forms\Components\TextInput::make('orden')
                    ->numeric()->default(0),
                Forms\Components\Toggle::make('activo')->default(true),
                Forms\Components\Textarea::make('descripcion')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('orden')
                    ->sortable()->label('#'),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->badge(),
                Tables\Columns\TextColumn::make('mobiliarios_count')
                    ->label('Mobiliarios')->counts('mobiliarios'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->reorderable('orden')
            ->defaultSort('orden')
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
            'index'  => Pages\ListCategoriasMobiliario::route('/'),
            'create' => Pages\CreateCategoriaMobiliario::route('/create'),
            'edit'   => Pages\EditCategoriaMobiliario::route('/{record}/edit'),
        ];
    }
}
