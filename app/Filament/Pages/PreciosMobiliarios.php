<?php

namespace App\Filament\Pages;

use App\Filament\Resources\MobiliarioResource;
use App\Models\Mobiliario;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PreciosMobiliarios extends BasePage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Mobiliario';
    protected static ?string $navigationLabel = 'Lista de Precios';
    protected static ?string $title           = 'Lista de Precios de Mobiliarios';
    protected static ?int    $navigationSort  = 3;

    protected static string $view = 'filament.pages.precios-mobiliarios';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mobiliario::query()
                    ->with(['marcas', 'media', 'atributos'])
                    ->withoutGlobalScopes()
            )
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
