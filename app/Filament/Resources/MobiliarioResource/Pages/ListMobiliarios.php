<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Exports\MobiliariosExport;
use App\Filament\Resources\MobiliarioResource;
use App\Models\Marca;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListMobiliarios extends ListRecords
{
    protected static string $resource = MobiliarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pendientesEntrega')
                ->label('Pendientes de Entrega')
                ->icon('heroicon-o-truck')
                ->color('warning')
                ->url(MobiliarioResource::getUrl('pendientes-entrega')),
            Actions\Action::make('exportarExcel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $query = $this->getTableQueryForExport();

                    return Excel::download(
                        new MobiliariosExport($query),
                        'mobiliarios-' . now()->format('Y-m-d_His') . '.xlsx',
                    );
                }),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = static::getResource()::getEloquentQuery();

        $counts = (clone $baseQuery)
            ->join('marca_mobiliario', 'marca_mobiliario.mobiliario_id', '=', 'mobiliarios.id')
            ->selectRaw('marca_mobiliario.marca_id as marca_id, count(*) as aggregate')
            ->groupBy('marca_mobiliario.marca_id')
            ->pluck('aggregate', 'marca_id');

        $sinMarca = (clone $baseQuery)->whereDoesntHave('marcas')->count();

        $tabs = [
            'todos' => Tab::make('Todos')
                ->badge((clone $baseQuery)->count()),
        ];

        Marca::query()
            ->whereIn('id', $counts->keys()->filter()->all())
            ->orderBy('nombre')
            ->get()
            ->each(function (Marca $marca) use (&$tabs, $counts): void {
                $tabs['marca_' . $marca->id] = Tab::make($marca->nombre)
                    ->modifyQueryUsing(fn (Builder $query) => $query->whereHas(
                        'marcas',
                        fn (Builder $marcasQuery) => $marcasQuery->where('marcas.id', $marca->id),
                    ))
                    ->badge($counts[$marca->id] ?? 0);
            });

        if ($sinMarca > 0) {
            $tabs['sin_marca'] = Tab::make('Sin marca')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('marcas'))
                ->badge($sinMarca);
        }

        return $tabs;
    }
}
