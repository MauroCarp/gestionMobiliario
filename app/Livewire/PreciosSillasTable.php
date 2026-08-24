<?php

namespace App\Livewire;

use App\Filament\Resources\InsumoResource;
use App\Models\InsumoMarcaSilla;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class PreciosSillasTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public string $activeMarcaTab = 'todos';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->sillasQuery())
            ->heading('Sillas')
            ->description('Precio de venta del producto según marca y nombre de fantasía.')
            ->columns([
                SpatieMediaLibraryImageColumn::make('insumo.imagen')
                    ->collection('imagen')
                    ->conversion('thumb')
                    ->label('Foto')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit:contain; background:#f3f4f6;']),

                TextColumn::make('insumo.codigo')
                    ->label('Código')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nombre_fantasia')
                    ->label('Nombre de fantasía')
                    ->placeholder('—')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('insumo.nombre')
                    ->label('Nombre real')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextInputColumn::make('precio')
                    ->label('Precio ($)')
                    ->type('number')
                    ->placeholder('—')
                    ->rules(['nullable', 'numeric', 'min:0'])
                    ->disabled(fn (): bool => ! auth()->user()?->hasRole('Administrador'))
                    ->sortable(),
            ])
            ->defaultSort('marca_id')
            ->striped()
            ->actions([
                Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (InsumoMarcaSilla $record): ?string => $record->insumo_id
                        ? InsumoResource::getUrl('view', ['record' => $record->insumo_id])
                        : null
                    ),
            ]);
    }

    protected function sillasQuery(): Builder
    {
        $query = InsumoMarcaSilla::query()
            ->with(['insumo.media', 'marca'])
            ->whereHas('insumo', function (Builder $insumoQuery): void {
                $insumoQuery
                    ->where('activo', true)
                    ->whereHas(
                        'categoriasInsumo',
                        fn (Builder $categoriasQuery) => $categoriasQuery->whereRaw("LOWER(nombre) LIKE '%silla%'")
                    );
            });

        if ($this->activeMarcaTab === 'sin_marca') {
            return $query->whereRaw('0 = 1');
        }

        if (str_starts_with($this->activeMarcaTab, 'marca_')) {
            $marcaId = (int) str_replace('marca_', '', $this->activeMarcaTab);

            if ($marcaId > 0) {
                $query->where('marca_id', $marcaId);
            }
        }

        return $query;
    }

    public function render()
    {
        return view('livewire.precios-sillas-table');
    }
}
