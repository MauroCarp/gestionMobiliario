<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresupuestoResource\Pages;
use App\Filament\Resources\PresupuestoResource\RelationManagers;
use App\Models\Presupuesto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresupuestoResource extends Resource
{
    protected static ?string $model = Presupuesto::class;
    protected static ?string $navigationIcon   = 'heroicon-o-document-text';
    protected static ?string $navigationGroup  = 'Operaciones';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $modelLabel       = 'Presupuesto';
    protected static ?string $pluralModelLabel = 'Presupuestos';

    // ─── Form ─────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Información del Presupuesto')
                ->schema([
                    Forms\Components\TextInput::make('codigo')
                        ->label('Código')
                        ->placeholder('Auto-generado al guardar')
                        ->disabled()
                        ->dehydrated(false)
                        ->visibleOn('edit')
                        ->columnSpan(1),

                    Forms\Components\Placeholder::make('estado_display')
                        ->label('Estado actual')
                        ->content(fn (?Presupuesto $record): string =>
                            $record
                                ? (Presupuesto::ESTADOS[$record->estado] ?? $record->estado)
                                : 'Borrador'
                        )
                        ->visibleOn('edit')
                        ->columnSpan(1),

                    Forms\Components\Placeholder::make('version_display')
                        ->label('Versión')
                        ->content(fn (?Presupuesto $record): string =>
                            $record ? 'v' . $record->version : 'v1'
                        )
                        ->visibleOn('edit')
                        ->columnSpan(1),

                    Forms\Components\Select::make('proyecto_id')
                        ->label('Proyecto')
                        ->relationship(
                            'proyecto',
                            'codigo_interno',
                            fn (Builder $query) => $query->with('agencia')->orderBy('codigo_interno')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn ($record) => $record->codigo_interno . ' — ' . ($record->agencia->nombre ?? '—')
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\Select::make('responsable_id')
                        ->label('Responsable')
                        ->relationship('responsable', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(fn () => auth()->id())
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('fecha_emision')
                        ->label('Fecha de Emisión')
                        ->required()
                        ->default(now())
                        ->columnSpan(1),
                ])
                ->columns(3),

            Forms\Components\Section::make('Items del Presupuesto')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Forms\Components\Select::make('mobiliario_id')
                                ->label('Mobiliario')
                                ->options(
                                    fn () => \App\Models\Mobiliario::orderBy('nombre')
                                        ->get()
                                        ->mapWithKeys(fn ($m) => [
                                            $m->id => "[{$m->codigo_interno}] {$m->nombre}",
                                        ])
                                )
                                ->searchable()
                                ->required()
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->integer()
                                ->minValue(1)
                                ->default(1)
                                ->required()
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('precio_unitario')
                                ->label('Precio Unit. $')
                                ->numeric()
                                ->minValue(0)
                                ->nullable()
                                ->placeholder('Opcional')
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('descripcion_override')
                                ->label('Descripción personalizada')
                                ->nullable()
                                ->placeholder('Dejar vacío para usar la descripción del mobiliario')
                                ->columnSpan(4),

                            Forms\Components\Textarea::make('observaciones')
                                ->label('Observaciones')
                                ->rows(2)
                                ->nullable()
                                ->columnSpan(2),

                            Forms\Components\Textarea::make('notas_manuales')
                                ->label('Notas para planilla impresa')
                                ->rows(2)
                                ->nullable()
                                ->placeholder('Anotaciones que aparecerán en el PDF')
                                ->columnSpan(2),

                        ])
                        ->columns(4)
                        ->orderColumn('orden')
                        ->addActionLabel('+ Agregar mobiliario')
                        ->defaultItems(0)
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string =>
                            isset($state['mobiliario_id'])
                                ? \App\Models\Mobiliario::find($state['mobiliario_id'])?->nombre
                                : null
                        )
                        ->collapsible(),
                ]),

            Forms\Components\Section::make('Observaciones y Notas')
                ->schema([
                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones generales')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('notas_internas')
                        ->label('Notas internas (no aparecen en PDF)')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsible(),
        ]);
    }

    // ─── Table ────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('proyecto.codigo_interno')
                    ->label('Proyecto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('proyecto.agencia.nombre')
                    ->label('Agencia')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => Presupuesto::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => Presupuesto::ESTADOS[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('version')
                    ->label('Ver.')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('fecha_emision')
                    ->label('Emisión')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(Presupuesto::ESTADOS),

                Tables\Filters\SelectFilter::make('proyecto_id')
                    ->label('Proyecto')
                    ->relationship('proyecto', 'codigo_interno'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('pdf')
                        ->label('Exportar PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger')
                        ->url(fn (Presupuesto $record) => route('presupuesto.pdf', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('excel')
                        ->label('Exportar Excel')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->url(fn (Presupuesto $record) => route('presupuesto.excel', $record->id))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('enviarRevision')
                        ->label('Enviar a Revisión')
                        ->icon('heroicon-o-arrow-right-circle')
                        ->color('warning')
                        ->visible(fn (Presupuesto $record): bool => $record->puedeEnviarARevision())
                        ->requiresConfirmation()
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('en_revision');
                            Notification::make()->success()->title('Enviado a revisión')->send();
                        }),

                    Tables\Actions\Action::make('aprobar')
                        ->label('Aprobar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Presupuesto $record): bool => $record->puedeAprobar())
                        ->requiresConfirmation()
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('aprobado');
                            Notification::make()->success()->title('Presupuesto aprobado')->send();
                        }),

                    Tables\Actions\Action::make('rechazar')
                        ->label('Rechazar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Presupuesto $record): bool => $record->puedeRechazar())
                        ->form([
                            Forms\Components\Textarea::make('comentario')
                                ->label('Motivo del rechazo')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (Presupuesto $record, array $data): void {
                            $record->cambiarEstado('rechazado', $data['comentario']);
                            Notification::make()->warning()->title('Presupuesto rechazado')->send();
                        }),

                    Tables\Actions\Action::make('confirmar')
                        ->label('Confirmar')
                        ->icon('heroicon-o-hand-thumb-up')
                        ->color('info')
                        ->visible(fn (Presupuesto $record): bool => $record->estado === 'aprobado')
                        ->requiresConfirmation()
                        ->modalHeading('Confirmar presupuesto')
                        ->modalDescription('Se reservará el stock de insumos y se generará una orden de compra si hay faltantes.')
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('confirmado');
                            Notification::make()->success()->title('Presupuesto confirmado. Stock reservado.')->send();
                        }),

                    Tables\Actions\Action::make('marcarPagado')
                        ->label('Marcar pagado')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->visible(fn (Presupuesto $record): bool => $record->estado === 'confirmado')
                        ->requiresConfirmation()
                        ->modalHeading('Registrar pago')
                        ->modalDescription('El stock de insumos será descontado definitivamente.')
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('pagado');
                            Notification::make()->success()->title('Presupuesto pagado. Stock descontado.')->send();
                        }),

                    Tables\Actions\Action::make('cancelar')
                        ->label('Cancelar')
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->color('gray')
                        ->visible(fn (Presupuesto $record): bool => $record->puedeCancelar())
                        ->requiresConfirmation()
                        ->action(function (Presupuesto $record): void {
                            $record->cambiarEstado('cancelado');
                            Notification::make()->info()->title('Presupuesto cancelado')->send();
                        }),

                    Tables\Actions\Action::make('nuevaVersion')
                        ->label('Nueva Versión')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->visible(fn (Presupuesto $record): bool =>
                            in_array($record->estado, ['aprobado', 'rechazado'])
                        )
                        ->form([
                            Forms\Components\Textarea::make('motivo')
                                ->label('Motivo de la nueva versión')
                                ->rows(2)
                                ->nullable(),
                        ])
                        ->action(function (Presupuesto $record, array $data): void {
                            $record->crearVersion($data['motivo'] ?? null);
                            $nuevoNumero = $record->version + 1;
                            $record->update(['version' => $nuevoNumero]);
                            $record->cambiarEstado('borrador', 'Nueva versión: v' . $nuevoNumero);
                            Notification::make()->success()
                                ->title("Nueva versión creada: v{$nuevoNumero}")
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    // ─── Relation managers ────────────────────────────────────────────────────

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\VersionesRelationManager::class,
            RelationManagers\HistorialPresupuestoRelationManager::class,
        ];
    }

    // ─── Pages ────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPresupuestos::route('/'),
            'create' => Pages\CreatePresupuesto::route('/create'),
            'edit'   => Pages\EditPresupuesto::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
