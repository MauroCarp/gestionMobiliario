<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TerceroResource\Pages;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TerceroResource extends Resource
{
    protected static ?string $model = Tercero::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Tercerizados';
    protected static ?string $modelLabel      = 'Tercero';
    protected static ?string $pluralModelLabel = 'Terceros';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tipo')
                    ->options(Tercero::TIPOS)
                    ->default('servicio_externo')
                    ->required(),
                Forms\Components\TextInput::make('especialidad')
                    ->label('Especialidad')
                    ->maxLength(255)
                    ->placeholder('Herrería, Pintura, Corte de vidrio…'),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contacto')
                    ->label('Persona de contacto')
                    ->maxLength(255),
                Forms\Components\Toggle::make('activo')
                    ->default(true),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => Tercero::TIPO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => Tercero::TIPOS[$state] ?? $state),
                Tables\Columns\TextColumn::make('especialidad')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('contacto')
                    ->label('Contacto')
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->options(Tercero::TIPOS),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTerceros::route('/'),
            'create' => Pages\CreateTercero::route('/create'),
            'edit'   => Pages\EditTercero::route('/{record}/edit'),
        ];
    }
}
