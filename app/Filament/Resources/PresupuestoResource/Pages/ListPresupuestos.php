<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Resources\PresupuestoResource;
use App\Models\Marca;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListPresupuestos extends ListRecords
{
    protected static string $resource = PresupuestoResource::class;

    protected static string $view = 'filament.resources.presupuestos.list-records';

    #[Url]
    public ?string $activeMarcaTab = null;

    /**
     * @var array<string, Tab>
     */
    protected array $cachedMarcaTabs;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->loadDefaultActiveTab();
        $this->loadDefaultActiveMarcaTab();
    }

    protected function loadDefaultActiveMarcaTab(): void
    {
        if (filled($this->activeMarcaTab)) {
            return;
        }

        $this->activeMarcaTab = 'todos';
    }

    public function updatedActiveMarcaTab(): void
    {
        $this->resetPage();
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
            'todos'             => Tab::make('Todos')->badge($total),
            'borrador'          => Tab::make('Borrador')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'borrador'))->badge($counts['borrador'] ?? 0),
            'en_revision'       => Tab::make('En Revisión')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'en_revision'))->badge($counts['en_revision'] ?? 0),
            'aprobado'          => Tab::make('Aprobados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'aprobado'))->badge($counts['aprobado'] ?? 0),
            'confirmado'        => Tab::make('Confirmados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'confirmado'))->badge($counts['confirmado'] ?? 0),
            'pagado'            => Tab::make('Pagados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'pagado'))->badge($counts['pagado'] ?? 0),
            'entregado_parcial' => Tab::make('Entregado parcial')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'entregado_parcial'))->badge($counts['entregado_parcial'] ?? 0),
            'entregado'         => Tab::make('Entregados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'entregado'))->badge($counts['entregado'] ?? 0),
            'rechazado'         => Tab::make('Rechazados')->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'rechazado'))->badge($counts['rechazado'] ?? 0),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getMarcaTabs(): array
    {
        $baseQuery = static::getResource()::getEloquentQuery();

        $counts = (clone $baseQuery)
            ->join('agencias', 'presupuestos.agencia_id', '=', 'agencias.id')
            ->join('proyectos', 'agencias.proyecto_id', '=', 'proyectos.id')
            ->selectRaw('proyectos.marca_id, count(distinct presupuestos.id) as aggregate')
            ->groupBy('proyectos.marca_id')
            ->pluck('aggregate', 'marca_id');

        $sinMarcaCount = (clone $baseQuery)
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('agencia_id')
                    ->orWhereHas('agencia', fn (Builder $q) => $q->whereNull('proyecto_id'))
                    ->orWhereHas('proyecto', fn (Builder $q) => $q->whereNull('marca_id'));
            })
            ->count();

        $tabs = [
            'todos' => Tab::make('Todas las marcas')
                ->badge((clone $baseQuery)->count()),
        ];

        Marca::query()
            ->whereIn('id', $counts->keys()->filter()->all())
            ->orderBy('nombre')
            ->get()
            ->each(function (Marca $marca) use (&$tabs, $counts): void {
                $tabs['marca_' . $marca->id] = Tab::make($marca->nombre)
                    ->modifyQueryUsing(fn (Builder $query) => $query->whereHas(
                        'proyecto',
                        fn (Builder $q) => $q->where('marca_id', $marca->id),
                    ))
                    ->badge($counts[$marca->id] ?? 0);
            });

        if ($sinMarcaCount > 0) {
            $tabs['sin_marca'] = Tab::make('Sin marca')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function (Builder $q): void {
                    $q
                        ->whereNull('agencia_id')
                        ->orWhereHas('agencia', fn (Builder $aq) => $aq->whereNull('proyecto_id'))
                        ->orWhereHas('proyecto', fn (Builder $pq) => $pq->whereNull('marca_id'));
                }))
                ->badge($sinMarcaCount);
        }

        return $tabs;
    }

    /**
     * @return array<string, Tab>
     */
    public function getCachedMarcaTabs(): array
    {
        return $this->cachedMarcaTabs ??= $this->getMarcaTabs();
    }

    protected function modifyQueryWithActiveMarcaTab(Builder $query): Builder
    {
        if (blank($this->activeMarcaTab) || $this->activeMarcaTab === 'todos') {
            return $query;
        }

        $tabs = $this->getCachedMarcaTabs();

        if (! array_key_exists($this->activeMarcaTab, $tabs)) {
            return $query;
        }

        return $tabs[$this->activeMarcaTab]->modifyQuery($query);
    }

    protected function makeTable(): Table
    {
        return parent::makeTable()
            ->modifyQueryUsing($this->modifyQueryWithActiveMarcaTab(...));
    }
}
