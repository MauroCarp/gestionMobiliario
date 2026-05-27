<?php

namespace App\Filament\Resources\LoteProcesoExternoResource\RelationManagers;

use App\Models\Insumo;
use App\Models\LoteEtapa;
use App\Models\LoteProcesoExterno;
use App\Models\TipoProcesoExterno;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EtapasRelationManager extends RelationManager
{
    protected static string $relationship = 'etapas';
    protected static ?string $title = 'Etapas del proceso';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(),
            Forms\Components\Select::make('tipo_proceso_id')
                ->label('Tipo de proceso')
                ->options(fn () => TipoProcesoExterno::where('activo', true)->pluck('nombre', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('tercero_id')
                ->label('Tercero')
                ->options(fn () => Tercero::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('estado')
                ->options(LoteEtapa::ESTADOS)
                ->default('pendiente')
                ->required(),
            Forms\Components\DatePicker::make('fecha_envio')
                ->label('Fecha de envío')
                ->nullable(),
            Forms\Components\DatePicker::make('fecha_recepcion_estimada')
                ->label('Recepción estimada')
                ->nullable(),
            Forms\Components\DatePicker::make('fecha_recepcion_real')
                ->label('Recepción real')
                ->nullable(),
            Forms\Components\TextInput::make('costo')
                ->label('Costo')
                ->numeric()
                ->prefix('$')
                ->nullable(),
            Forms\Components\Textarea::make('observaciones')
                ->rows(2)
                ->nullable()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('orden')
            ->defaultSort('orden')
            ->columns([
                Tables\Columns\TextColumn::make('orden')
                    ->label('#')
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipoProceso.nombre')
                    ->label('Proceso')
                    ->badge()
                    ->color(fn ($record) => $record->tipoProceso?->color ?? 'gray'),
                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->label('Tercero')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => LoteEtapa::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => LoteEtapa::ESTADOS[$state] ?? $state),
                Tables\Columns\TextColumn::make('fecha_envio')
                    ->label('Enviado')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('fecha_recepcion_estimada')
                    ->label('Recep. est.')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('fecha_recepcion_real')
                    ->label('Recep. real')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo')
                    ->money('ARS')
                    ->placeholder('—'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => ! in_array($this->ownerRecord->estado, ['completado', 'cancelado'])),
            ])
            ->actions([
                // Enviar al tercero: pendiente → en_transito
                Tables\Actions\Action::make('enviar')
                    ->label('Enviar')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (LoteEtapa $record): bool =>
                        $record->estado === 'pendiente' &&
                        ! in_array($this->ownerRecord->estado, ['completado', 'cancelado'])
                    )
                    ->form([
                        Forms\Components\DatePicker::make('fecha_envio')
                            ->label('Fecha de envío')
                            ->default(now()->toDateString())
                            ->required(),
                        Forms\Components\Textarea::make('observaciones')
                            ->rows(2)
                            ->nullable(),
                    ])
                    ->action(function (LoteEtapa $record, array $data): void {
                        if (! $record->puedeIniciar()) {
                            Notification::make()
                                ->title('No se puede enviar aún')
                                ->body('Hay etapas anteriores sin completar.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $record->update([
                            'estado'       => 'en_transito',
                            'fecha_envio'  => $data['fecha_envio'],
                            'observaciones'=> $data['observaciones'] ?? $record->observaciones,
                            'usuario_id'   => auth()->id(),
                        ]);

                        if ($this->ownerRecord->estado === 'pendiente') {
                            $this->ownerRecord->update([
                                'estado'       => 'en_proceso',
                                'fecha_inicio' => now()->toDateString(),
                            ]);
                        }

                        Notification::make()->title('Enviado al tercero')->success()->send();
                    }),

                // Tercero recibió el material: en_transito → en_proceso
                Tables\Actions\Action::make('recibido_tercero')
                    ->label('En proceso')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->visible(fn (LoteEtapa $record): bool =>
                        $record->estado === 'en_transito' &&
                        ! in_array($this->ownerRecord->estado, ['completado', 'cancelado'])
                    )
                    ->action(function (LoteEtapa $record): void {
                        $record->update([
                            'estado'     => 'en_proceso',
                            'usuario_id' => auth()->id(),
                        ]);
                        Notification::make()->title('Etapa en proceso')->success()->send();
                    }),

                // Completar etapa: en_proceso → completado
                Tables\Actions\Action::make('completar')
                    ->label('Completar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (LoteEtapa $record): bool =>
                        $record->estado === 'en_proceso' &&
                        ! in_array($this->ownerRecord->estado, ['completado', 'cancelado'])
                    )
                    ->form([
                        Forms\Components\DatePicker::make('fecha_recepcion_real')
                            ->label('Fecha de recepción real')
                            ->default(now()->toDateString())
                            ->required(),
                        Forms\Components\TextInput::make('costo')
                            ->label('Costo de esta etapa ($)')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        Forms\Components\Textarea::make('observaciones')
                            ->rows(2)
                            ->nullable(),
                    ])
                    ->action(function (LoteEtapa $record, array $data): void {
                        $record->update([
                            'estado'               => 'completado',
                            'fecha_recepcion_real' => $data['fecha_recepcion_real'],
                            'costo'                => $data['costo'] ?? null,
                            'observaciones'        => $data['observaciones'] ?? $record->observaciones,
                            'usuario_id'           => auth()->id(),
                        ]);

                        $this->verificarCompletitudLote();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    private function verificarCompletitudLote(): void
    {
        $lote = $this->ownerRecord->fresh('etapas');

        if ($lote->etapas->every(fn ($e) => $e->estado === 'completado')) {
            $lote->update([
                'estado'                  => 'completado',
                'fecha_finalizacion_real' => now()->toDateString(),
            ]);

            if ($lote->entidad_tipo === 'insumo') {
                Insumo::find($lote->entidad_id)?->increment('stock_actual', $lote->cantidad);

                Notification::make()
                    ->title('¡Lote completado!')
                    ->body("Se agregaron {$lote->cantidad} unidades al stock.")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('¡Lote completado!')
                    ->body('El mobiliario ha finalizado todos sus procesos externos.')
                    ->success()
                    ->send();
            }
        } else {
            Notification::make()->title('Etapa completada')->success()->send();
        }
    }
}
