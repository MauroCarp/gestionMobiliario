<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Resources\PresupuestoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPresupuestos extends ListRecords
{
    protected static string $resource = PresupuestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = static::getResource()::getEloquentQuery();

        $counts = (clone $baseQuery)
            ->selectRaw('estado, count(*) as aggregate')
            ->groupBy('estado')
            ->pluck('aggregate', 'estado');
            
        $total = (clone $baseQuery)->count();
        
        return [
            'todos'       => Tab::make('Todos')->badge($total),
            'borrador'    => Tab::make('Borrador')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'borrador'))->badge(($counts['borrador']) ? $counts['borrador'] : 0),
            'en_revision' => Tab::make('En Revisión')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'en_revision'))->badge(isset($counts['en_revision']) ? $counts['en_revision'] : 0),
            'aprobado'    => Tab::make('Aprobados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'aprobado'))->badge(isset($counts['aprobado']) ? $counts['aprobado'] : 0),
            'confirmado'    => Tab::make('Confirmados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'confirmado'))->badge(isset($counts['confirmado']) ? $counts['confirmado'] : 0),
            'pagado'    => Tab::make('Pagados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'pagado'))->badge(isset($counts['pagado']) ? $counts['pagado'] : 0),
            'rechazado'   => Tab::make('Rechazados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'rechazado'))->badge(isset($counts['rechazado']) ? $counts['rechazado'] : 0),
        ];
    }
}
