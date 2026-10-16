<?php

namespace App\Filament\Resources\ReclamoResource\RelationManagers;

use App\Filament\Resources\ReclamoResource;
use App\Models\Reclamo;
use App\Models\ReclamoRegistro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RegistrosRelationManager extends RelationManager
{
    protected static string $relationship = 'registros';

    protected static ?string $title = 'Registros';

    protected static ?string $modelLabel = 'registro';

    protected static ?string $pluralModelLabel = 'registros';

    /**
     * @var array<int, string>|null
     */
    protected ?array $opcionesMobiliarioCache = null;

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->check();
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->camposAltaRegistro());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['mobiliario', 'insumo.marcasSilla', 'media']))
            ->columns([
                Tables\Columns\TextColumn::make('item')
                    ->label('Mobiliario / Silla')
                    ->wrap()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $item) use ($search): void {
                            $item
                                ->whereHas('mobiliario', fn (Builder $mobiliario) => $mobiliario
                                    ->where('nombre', 'like', "%{$search}%")
                                    ->orWhere('codigo_interno', 'like', "%{$search}%"))
                                ->orWhereHas('insumo', fn (Builder $insumo) => $insumo
                                    ->where('nombre', 'like', "%{$search}%"));
                        });
                    })
                    ->getStateUsing(function (ReclamoRegistro $record): string {
                        $reclamo = $this->getOwnerRecord();
                        $marcaId = $reclamo instanceof Reclamo
                            ? $reclamo->agencia?->proyecto?->marca_id
                            : null;

                        return $record->nombreItem($marcaId);
                    }),
                Tables\Columns\TextColumn::make('observacion')
                    ->label('Observación')
                    ->wrap()
                    ->placeholder('—')
                    ->limit(80),
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => ReclamoRegistro::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => ReclamoRegistro::ESTADOS[$state] ?? $state),
                Tables\Columns\TextColumn::make('costo')
                    ->money('ARS')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('costo_logistica')
                    ->label('Costo logística')
                    ->money('ARS')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('fecha_resuelto')
                    ->label('Fecha resuelto')
                    ->date('d/m/Y')
                    ->placeholder('—'),
            ])
            ->defaultSort('fecha', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo registro')
                    ->modalHeading('Nuevo registro')
                    ->disabled(fn (): bool => $this->opcionesItem() === [])
                    ->tooltip(fn (): ?string => $this->opcionesItem() === []
                        ? 'Este presupuesto no tiene mobiliarios ni sillas para reclamar.'
                        : null)
                    ->before(function (Tables\Actions\CreateAction $action): void {
                        if ($this->opcionesItem() === []) {
                            Notification::make()
                                ->danger()
                                ->title('Este presupuesto no tiene mobiliarios ni sillas para reclamar.')
                                ->send();

                            $action->halt();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->infolist($this->registroInfolist())
                    ->extraModalFooterActions([
                        $this->verImagenesAction('verImagenesDesdeVista'),
                        $this->cambiarEstadoAction('cambiarEstadoDesdeVista'),
                    ]),
                $this->verImagenesAction(),
                $this->cambiarEstadoAction(),
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar registro'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function canCreate(): bool
    {
        return auth()->check();
    }

    protected function canEdit(Model $record): bool
    {
        return auth()->check();
    }

    protected function canDelete(Model $record): bool
    {
        return auth()->check();
    }

    protected function canView(Model $record): bool
    {
        return auth()->check();
    }

    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    protected function camposAltaRegistro(): array
    {
        return [
            ...ReclamoResource::camposSelectorItem(fn (): ?int => $this->getOwnerRecord() instanceof Reclamo
                ? $this->getOwnerRecord()->presupuesto_id
                : null),
            Forms\Components\Textarea::make('observacion')
                ->label('Observación')
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\DatePicker::make('fecha')
                ->label('Fecha')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y')
                ->default(fn () => now()),
            ReclamoResource::campoImagenesRegistro(),
        ];
    }

    /**
     * @return array<int, \Filament\Infolists\Components\Component>
     */
    protected function registroInfolist(): array
    {
        return [
            Infolists\Components\TextEntry::make('item')
                ->label('Mobiliario / Silla')
                ->state(function (ReclamoRegistro $record): string {
                    $reclamo = $this->getOwnerRecord();
                    $marcaId = $reclamo instanceof Reclamo
                        ? $reclamo->agencia?->proyecto?->marca_id
                        : null;

                    return $record->nombreItem($marcaId);
                }),
            Infolists\Components\TextEntry::make('observacion')
                ->label('Observación')
                ->placeholder('—')
                ->columnSpanFull(),
            Infolists\Components\TextEntry::make('fecha')
                ->date('d/m/Y'),
            Infolists\Components\TextEntry::make('estado')
                ->badge()
                ->color(fn (string $state): string => ReclamoRegistro::ESTADO_COLORS[$state] ?? 'gray')
                ->formatStateUsing(fn (string $state): string => ReclamoRegistro::ESTADOS[$state] ?? $state),
            Infolists\Components\TextEntry::make('costo')
                ->money('ARS')
                ->placeholder('—'),
            Infolists\Components\TextEntry::make('costo_logistica')
                ->label('Costo logística')
                ->money('ARS')
                ->placeholder('—'),
            Infolists\Components\TextEntry::make('fecha_resuelto')
                ->label('Fecha resuelto')
                ->date('d/m/Y')
                ->placeholder('—'),
            Infolists\Components\ViewEntry::make('galeria')
                ->label('Galería')
                ->state(fn (ReclamoRegistro $record) => $record->getMedia('imagenes'))
                ->view('filament.reclamos.registro-imagenes')
                ->visible(fn (ReclamoRegistro $record): bool => $record->hasMedia('imagenes'))
                ->columnSpanFull(),
        ];
    }

    protected function verImagenesAction(string $name = 'verImagenes'): Tables\Actions\Action
    {
        return Tables\Actions\Action::make($name)
            ->label('Galería')
            ->icon('heroicon-o-photo')
            ->color('gray')
            ->badge(fn (ReclamoRegistro $record): ?int => $record->getMedia('imagenes')->count() ?: null)
            ->modalHeading(fn (ReclamoRegistro $record): string => 'Galería — '.$record->etiquetaListado())
            ->modalWidth('5xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->modalContent(fn (ReclamoRegistro $record) => view('filament.reclamos.registro-imagenes', [
                'imagenes' => $record->getMedia('imagenes'),
            ]));
    }

    protected function cambiarEstadoAction(string $name = 'cambiarEstado'): Tables\Actions\Action
    {
        return Tables\Actions\Action::make($name)
            ->label('Cambiar estado')
            ->icon('heroicon-o-arrow-path')
            ->color(fn (ReclamoRegistro $record): string => $record->estaPendiente() ? 'success' : 'warning')
            ->modalHeading(fn (ReclamoRegistro $record): string => $record->estaPendiente()
                ? 'Marcar como resuelto'
                : 'Volver a pendiente')
            ->modalDescription(fn (ReclamoRegistro $record): ?string => $record->estaResuelto()
                ? 'El registro volverá a Pendiente. Se conservan el costo, el costo de logística y la fecha resuelto.'
                : null)
            ->modalSubmitActionLabel(fn (ReclamoRegistro $record): string => $record->estaPendiente()
                ? 'Marcar resuelto'
                : 'Volver a pendiente')
            ->fillForm(fn (ReclamoRegistro $record): array => [
                'fecha_resuelto' => $record->fecha_resuelto?->toDateString() ?? now()->toDateString(),
                'costo' => $record->costo,
                'costo_logistica' => $record->costo_logistica,
            ])
            ->form(fn (ReclamoRegistro $record): array => $record->estaPendiente()
                ? [
                    Forms\Components\DatePicker::make('fecha_resuelto')
                        ->label('Fecha resuelto')
                        ->required(),
                    Forms\Components\TextInput::make('costo')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('$'),
                    Forms\Components\TextInput::make('costo_logistica')
                        ->label('Costo logística')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('$'),
                ]
                : [])
            ->action(function (ReclamoRegistro $record, array $data): void {
                if ($record->estaPendiente()) {
                    $record->update([
                        'estado' => 'resuelto',
                        'fecha_resuelto' => $data['fecha_resuelto'],
                        'costo' => $data['costo'] === null || $data['costo'] === '' ? null : $data['costo'],
                        'costo_logistica' => $data['costo_logistica'] === null || $data['costo_logistica'] === '' ? null : $data['costo_logistica'],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Registro marcado como resuelto')
                        ->send();

                    return;
                }

                $record->update(['estado' => 'pendiente']);

                Notification::make()
                    ->success()
                    ->title('Registro vuelto a pendiente')
                    ->send();
            });
    }

    /**
     * @return array<string, string>
     */
    protected function opcionesItem(): array
    {
        if ($this->opcionesMobiliarioCache !== null) {
            return $this->opcionesMobiliarioCache;
        }

        $reclamo = $this->getOwnerRecord();

        if (! $reclamo instanceof Reclamo) {
            return $this->opcionesMobiliarioCache = [];
        }

        return $this->opcionesMobiliarioCache = $reclamo->opcionesItem();
    }
}
