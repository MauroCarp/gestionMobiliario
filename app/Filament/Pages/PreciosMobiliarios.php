<?php

namespace App\Filament\Pages;

use App\Filament\Resources\MobiliarioResource;
use App\Models\Marca;
use App\Models\Mobiliario;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class PreciosMobiliarios extends BasePage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $navigationLabel = 'Lista de Precios';
    protected static ?string $title           = 'Lista de Precios de Mobiliarios';
    protected static ?int    $navigationSort  = 3;

    protected static string $view = 'filament.pages.precios-mobiliarios';

    #[Url]
    public ?string $activeMarcaTab = 'todos';

    /**
     * @var array<string, Tab>
     */
    protected array $cachedMarcaTabs;

    public function updatedActiveMarcaTab(): void
    {
        $this->resetPage();
    }

    /**
     * @return array<string, Tab>
     */
    public function getMarcaTabs(): array
    {
        $baseQuery = Mobiliario::query()->withoutGlobalScopes();

        $counts = (clone $baseQuery)
            ->join('marca_mobiliario', 'marca_mobiliario.mobiliario_id', '=', 'mobiliarios.id')
            ->selectRaw('marca_mobiliario.marca_id as marca_id, count(*) as aggregate')
            ->groupBy('marca_mobiliario.marca_id')
            ->pluck('aggregate', 'marca_id');

        $sinMarca = (clone $baseQuery)->whereDoesntHave('marcas')->count();

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

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mobiliario::query()
                    ->with(['marcas', 'media', 'atributos'])
                    ->withoutGlobalScopes()
            )
            ->modifyQueryUsing($this->modifyQueryWithActiveMarcaTab(...))
            ->columns([
                SpatieMediaLibraryImageColumn::make('imagen_thumb')
                    ->collection('imagenes')
                    ->conversion('thumb')
                    ->label('Foto')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit:contain; background:#f3f4f6;']),

                TextColumn::make('codigo_interno')
                    ->label('Código')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('marcas.nombre')
                    ->label('Marcas')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('atributos_lista')
                    ->label('Atributos')
                    ->getStateUsing(fn (Mobiliario $record): string =>
                        $record->atributos->isEmpty()
                            ? '—'
                            : $record->atributos
                                ->map(fn ($a) => "<strong>{$a->clave}:</strong> {$a->valor}")
                                ->implode('<br>')
                    )
                    ->html()
                    ->wrap(),

                TextInputColumn::make('precio')
                    ->label('Precio ($)')
                    ->type('number')
                    ->placeholder('—')
                    ->rules(['nullable', 'numeric', 'min:0'])
                    ->disabled(fn (): bool => ! auth()->user()?->hasRole('Administrador'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('marcas')
                    ->label('Marca')
                    ->relationship('marcas', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('nombre')
            ->striped()
            ->actions([
                \Filament\Tables\Actions\Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Mobiliario $record): string =>
                        MobiliarioResource::getUrl('view', ['record' => $record])
                    ),
            ]);
    }
}
