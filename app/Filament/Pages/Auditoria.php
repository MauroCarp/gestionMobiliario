<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Services\DatabaseBackupService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class Auditoria extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Auditoría';

    protected static ?string $title = 'Auditoría del sistema';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.auditoria';

    protected function getHeaderActions(): array
    {
        $destino = config('backup.path');

        return [
            Action::make('backupBaseDatos')
                ->label('Backup de base de datos')
                ->icon('heroicon-o-circle-stack')
                ->color('warning')
                ->visible(fn (): bool => auth()->user()?->hasRole('Administrador') ?? false)
                ->requiresConfirmation()
                ->modalHeading('Generar backup de la base de datos')
                ->modalDescription("Se creará un archivo .sql en:\n{$destino}")
                ->action(function () use ($destino): void {
                    try {
                        $resultado = app(DatabaseBackupService::class)->ejecutar();

                        activity()
                            ->causedBy(auth()->user())
                            ->log('Backup de base de datos: '.$resultado['archivo']);

                        $tamano = number_format($resultado['bytes'] / 1024 / 1024, 2, ',', '.');

                        Notification::make()
                            ->success()
                            ->title('Backup generado')
                            ->body("{$resultado['archivo']} ({$tamano} MB)\n{$destino}")
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->danger()
                            ->title('No se pudo generar el backup')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::query()->with('causer')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Usuario')
                    ->placeholder('Sistema')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Acción')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Creado',
                        'updated' => 'Modificado',
                        'deleted' => 'Eliminado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Modelo')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '—')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('properties')
                    ->label('Cambios')
                    ->formatStateUsing(function ($state): string {
                        if (empty($state)) {
                            return '—';
                        }
                        $data = is_array($state) ? $state : json_decode($state, true);
                        $changed = array_keys($data['attributes'] ?? $data ?? []);

                        return implode(', ', array_slice($changed, 0, 5));
                    })
                    ->wrap()
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('description')
                    ->label('Acción')
                    ->options([
                        'created' => 'Creado',
                        'updated' => 'Modificado',
                        'deleted' => 'Eliminado',
                    ]),

                Tables\Filters\SelectFilter::make('causer_id')
                    ->label('Usuario')
                    ->options(fn () => User::orderBy('name')->pluck('name', 'id')->toArray()),

                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Modelo')
                    ->options(fn () => Activity::query()
                        ->whereNotNull('subject_type')
                        ->distinct()
                        ->pluck('subject_type', 'subject_type')
                        ->map(fn ($v) => class_basename($v))
                        ->toArray()
                    ),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q) => $q->whereDate('created_at', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($q) => $q->whereDate('created_at', '<=', $data['hasta']));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}
