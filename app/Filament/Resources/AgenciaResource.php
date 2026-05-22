<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgenciaResource\Pages;
use App\Models\Agencia;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgenciaResource extends Resource
{
    protected static ?string $model = Agencia::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel = 'Agencia';
    protected static ?string $pluralModelLabel = 'Agencias';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos principales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('codigo_interno')
                    ->required()->maxLength(50)->unique(ignoreRecord: true),
                Forms\Components\Select::make('proyecto_id')
                    ->label('Proyecto')
                    ->options(fn () => Proyecto::with('marca')
                        ->orderBy('codigo_interno')
                        ->get()
                        ->mapWithKeys(fn ($p) => [
                            $p->id => $p->codigo_interno . ' — ' . ($p->marca->nombre ?? '—'),
                        ]))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('prioridad')
                    ->options([1 => 'Alta', 2 => 'Media', 3 => 'Baja'])
                    ->default(3)->required(),
                Forms\Components\TextInput::make('responsable')->maxLength(255),
                Forms\Components\TextInput::make('direccion')->maxLength(255),
                Forms\Components\Toggle::make('activo')->default(true)->label('Activa'),
                Forms\Components\TagsInput::make('etiquetas')
                    ->separator(',')
                    ->label('Etiquetas')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Archivos adjuntos')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('archivos')
                    ->collection('archivos')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->label('Archivos generales'),
            ]),

            Forms\Components\Section::make('Planos')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('planos')
                    ->collection('planos')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/svg+xml'])
                    ->label('Planos / Layouts'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('proyecto.codigo_interno')
                    ->label('Proyecto')->badge()->color('info')->searchable(),
                Tables\Columns\TextColumn::make('proyecto.marca.nombre')
                    ->label('Marca')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('responsable')->searchable(),
                Tables\Columns\BadgeColumn::make('prioridad')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Alta', 2 => 'Media', default => 'Baja',
                    })
                    ->color(fn ($state) => match ($state) {
                        1 => 'danger', 2 => 'warning', default => 'success',
                    }),
                Tables\Columns\IconColumn::make('activo')->boolean(),
                Tables\Columns\TextColumn::make('presupuestos_count')
                    ->label('Presupuestos')->counts('presupuestos'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('proyecto_id')
                    ->label('Proyecto')
                    ->relationship('proyecto', 'codigo_interno')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('prioridad')
                    ->options([1 => 'Alta', 2 => 'Media', 3 => 'Baja']),
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
            'index'  => Pages\ListAgencias::route('/'),
            'create' => Pages\CreateAgencia::route('/create'),
            'edit'   => Pages\EditAgencia::route('/{record}/edit'),
        ];
    }
}
