<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoteProcesoExternoResource\Pages;
use App\Filament\Resources\LoteProcesoExternoResource\RelationManagers\EtapasRelationManager;
use App\Models\Insumo;
use App\Models\LoteProcesoExterno;
use App\Models\Mobiliario;
use App\Models\PlantillaFlujoExterno;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoteProcesoExternoResource extends Resource
{
    protected static ?string $model = LoteProcesoExterno::class;
    protected static ?string $navigationIcon  = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Tercerizados';
    protected static ?string $modelLabel      = 'Lote en proceso';
    protected static ?string $pluralModelLabel = 'Lotes en proceso';
    protected static ?int    $navigationSort  = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del lote')->schema([
                Forms\Components\Select::make('entidad_tipo')
                    ->label('Tipo de item')
                    ->options(['insumo' => 'Insumo', 'mobiliario' => 'Mobiliario'])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('entidad_id', null)),

                Forms\Components\Select::make('entidad_id')
                    ->label('Item')
                    ->options(fn (Forms\Get $get) => match ($get('entidad_tipo')) {
                        'insumo'     => Insumo::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'),
                        'mobiliario' => Mobiliario::orderBy('nombre')->pluck('nombre', 'id'),
                        default      => [],
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set): void {
                        $tipo = $get('entidad_tipo');
                        if ($state && $tipo) {
                            $plantilla = PlantillaFlujoExterno::where('entidad_tipo', $tipo)
                                ->where('entidad_id', $state)
                                ->where('activo', true)
                                ->first();
                            $set('plantilla_id', $plantilla?->id);
                        }
                    }),

                Forms\Components\Select::make('plantilla_id')
                    ->label('Plantilla de flujo')
                    ->options(fn (Forms\Get $get) => PlantillaFlujoExterno::where('entidad_tipo', $get('entidad_tipo') ?? '')
                        ->where('entidad_id', $get('entidad_id') ?? 0)
                        ->where('activo', true)
                        ->pluck('nombre', 'id'))
                    ->nullable()
                    ->helperText('Las etapas se crearán automáticamente al guardar.'),

                Forms\Components\TextInput::make('cantidad')
                    ->numeric()
                    ->minValue(0.01)
                    ->required(),

                Forms\Components\Select::make('origen_tipo')
                    ->label('Origen')
                    ->options(LoteProcesoExterno::ORIGENES)
                    ->default('manual')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha de inicio')
                    ->default(now()->toDateString()),

                Forms\Components\DatePicker::make('fecha_finalizacion_estimada')
                    ->label('Finalización estimada')
                    ->nullable(),

                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Datos del lote')->schema([
                Infolists\Components\TextEntry::make('codigo')
                    ->badge()
                    ->color('primary'),
                Infolists\Components\TextEntry::make('entidad_nombre')
                    ->label('Item'),
                Infolists\Components\TextEntry::make('entidad_tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'insumo'     => 'Insumo',
                        'mobiliario' => 'Mobiliario',
                        default      => $state,
                    }),
                Infolists\Components\TextEntry::make('cantidad')
                    ->numeric(2),
                Infolists\Components\TextEntry::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => LoteProcesoExterno::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => LoteProcesoExterno::ESTADOS[$state] ?? $state),
                Infolists\Components\TextEntry::make('origen_tipo')
                    ->label('Origen')
                    ->formatStateUsing(fn (string $state): string => LoteProcesoExterno::ORIGENES[$state] ?? $state),
                Infolists\Components\TextEntry::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('fecha_finalizacion_estimada')
                    ->label('Fin estimado')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('fecha_finalizacion_real')
                    ->label('Fin real')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('plantilla.nombre')
                    ->label('Plantilla')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('observaciones')
                    ->placeholder('—')
                    ->columnSpanFull(),
            ])->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('entidad_nombre')
                    ->label('Item')
                    ->searchable(query: fn ($query, string $search) => $query),
                Tables\Columns\TextColumn::make('entidad_tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'insumo' ? 'info' : 'warning')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'insumo'     => 'Insumo',
                        'mobiliario' => 'Mobiliario',
                        default      => $state,
                    }),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric(2)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => LoteProcesoExterno::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => LoteProcesoExterno::ESTADOS[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('etapa_actual.tipoProceso.nombre')
                    ->label('Etapa actual')
                    ->placeholder('—')
                    ->badge()
                    ->color(fn ($record) => $record->etapa_actual?->tipoProceso?->color ?? 'gray'),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_finalizacion_estimada')
                    ->label('Fin est.')
                    ->date('d/m/Y')
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(LoteProcesoExterno::ESTADOS),
                Tables\Filters\SelectFilter::make('entidad_tipo')
                    ->label('Tipo de item')
                    ->options(['insumo' => 'Insumo', 'mobiliario' => 'Mobiliario']),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('cancelar')
                    ->label('')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (LoteProcesoExterno $r): bool =>
                        ! in_array($r->estado, ['completado', 'cancelado'])
                    )
                    ->action(function (LoteProcesoExterno $record): void {
                        $record->update(['estado' => 'cancelado']);
                        Notification::make()->title('Lote cancelado')->warning()->send();
                    }),
                Tables\Actions\DeleteAction::make()
                ->label('')
                ->icon('heroicon-o-trash')
                ->color('danger'),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            EtapasRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLotesProcesoExterno::route('/'),
            'create' => Pages\CreateLoteProcesoExterno::route('/create'),
            'view'   => Pages\ViewLoteProcesoExterno::route('/{record}'),
            'edit'   => Pages\EditLoteProcesoExterno::route('/{record}/edit'),
        ];
    }
}
