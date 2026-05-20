<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del Proyecto')->schema([
                Forms\Components\TextInput::make('codigo_interno')
                    ->required()->maxLength(50)->unique(ignoreRecord: true),
                Forms\Components\Select::make('agencia_id')
                    ->label('Agencia')
                    ->relationship('agencia', 'nombre')
                    ->searchable()->preload()->required(),
                Forms\Components\Select::make('estado')
                    ->options(Proyecto::ESTADOS)
                    ->default('presupuestar')
                    ->required(),
                Forms\Components\DatePicker::make('fecha_inicio')
                    ->displayFormat('d/m/Y'),
                Forms\Components\DatePicker::make('fecha_entrega_estimada')
                    ->label('Entrega estimada')
                    ->displayFormat('d/m/Y'),
                Forms\Components\DatePicker::make('fecha_entrega_real')
                    ->label('Entrega real')
                    ->displayFormat('d/m/Y'),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Mobiliario del Proyecto')->schema([
                Forms\Components\Repeater::make('mobiliariosPivot')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('mobiliario_id')
                            ->label('Mobiliario')
                            ->relationship('mobiliario', 'nombre')
                            ->searchable()->preload()->required(),
                        Forms\Components\TextInput::make('cantidad')
                            ->numeric()->minValue(1)->default(1)->required(),
                        Forms\Components\TextInput::make('observaciones')->nullable(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Agregar mobiliario')
                    ->label('Items'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('agencia.nombre')
                    ->label('Agencia')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('agencia.marca.nombre')
                    ->label('Marca')->badge()->color('primary'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->formatStateUsing(fn ($state) => Proyecto::ESTADOS[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'presupuestar'  => 'gray',
                        'presupuestado' => 'warning',
                        'confirmado'    => 'info',
                        'en_produccion' => 'primary',
                        'entregado'     => 'success',
                        default         => 'gray',
                    }),
                Tables\Columns\TextColumn::make('fecha_entrega_estimada')
                    ->label('Entrega est.')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('fecha_entrega_real')
                    ->label('Entrega real')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(Proyecto::ESTADOS),
                Tables\Filters\SelectFilter::make('agencia_id')
                    ->label('Agencia')
                    ->relationship('agencia', 'nombre')
                    ->searchable()->preload(),
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
            RelationManagers\HistorialRelationManager::class,
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
            'index'  => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit'   => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
