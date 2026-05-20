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
        return [
            'todos'       => Tab::make('Todos'),
            'borrador'    => Tab::make('Borrador')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'borrador')),
            'en_revision' => Tab::make('En Revisión')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'en_revision')),
            'aprobado'    => Tab::make('Aprobados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'aprobado')),
            'rechazado'   => Tab::make('Rechazados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'rechazado')),
        ];
    }
}
