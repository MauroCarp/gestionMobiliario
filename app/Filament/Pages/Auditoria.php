<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Spatie\Activitylog\Models\Activity;

class Auditoria extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Auditoría';
    protected static ?string $title           = 'Auditoría del sistema';
    protected static ?int    $navigationSort  = 10;
    protected static string  $view            = 'filament.pages.auditoria';

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
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Creado',
                        'updated' => 'Modificado',
                        'deleted' => 'Eliminado',
                        default   => $state,
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
                    ->options(fn () => \App\Models\User::orderBy('name')->pluck('name', 'id')->toArray()),

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
                        \Filament\Forms\Components\DatePicker::make('desde')->label('Desde'),
                        \Filament\Forms\Components\DatePicker::make('hasta')->label('Hasta'),
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
