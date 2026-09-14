<?php

namespace App\Livewire;

use App\Filament\Pages\Impresora;
use App\Models\Empleado;
use App\Models\Marca;
use App\Models\Mobiliario;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component as LivewireComponent;

class ImpresoraModal extends LivewireComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /**
     * @var array<string, mixed>
     */
    public array $datosPendientes = [];

    #[On('abrir-impresora')]
    public function abrir(): void
    {
        if (! Impresora::canAccess()) {
            return;
        }

        $this->mountAction('imprimir');
    }

    public function imprimirAction(): Action
    {
        return Action::make('imprimir')
            ->label('Imprimir')
            ->icon('heroicon-o-printer')
            ->color('primary')
            ->modalHeading('Impresora')
            ->modalSubmitActionLabel('Imprimir')
            ->form($this->impresoraFormSchema())
            ->fillForm(fn (): array => $this->datosPendientes !== []
                ? $this->datosPendientes
                : ['cantidad' => 1, 'legajos' => []])
            ->action(function (array $data, Action $action): void {
                if ((int) $data['cantidad'] > 10) {
                    $this->datosPendientes = $data;
                    $this->replaceMountedAction('confirmarCantidad');

                    return;
                }

                $this->datosPendientes = $data;
                $this->abrirEtiqueta($data);
                $action->halt();
            });
    }

    public function confirmarCantidadAction(): Action
    {
        return Action::make('confirmarCantidad')
            ->label('Confirmar cantidad')
            ->requiresConfirmation()
            ->modalHeading('Cantidad mayor a 10')
            ->modalDescription('La cantidad es mayor a 10. ¿Desea continuar?')
            ->modalSubmitActionLabel('Continuar')
            ->action(function (): void {
                if ($this->datosPendientes === []) {
                    return;
                }

                $this->abrirEtiqueta($this->datosPendientes);
                $this->replaceMountedAction('imprimir');
            });
    }

    /**
     * @return array<int, Component>
     */
    protected function impresoraFormSchema(): array
    {
        return [
            Select::make('marca_id')
                ->label('Marca')
                ->options(fn () => Marca::query()->orderBy('nombre')->pluck('nombre', 'id'))
                ->searchable()
                ->preload()
                ->live()
                ->required()
                ->afterStateUpdated(fn (Set $set) => $set('mobiliario_id', null)),

            Select::make('mobiliario_id')
                ->label('Mobiliario')
                ->options(function (Get $get) {
                    $marcaId = $get('marca_id');

                    if (blank($marcaId)) {
                        return [];
                    }

                    return Mobiliario::query()
                        ->whereHas('marcas', fn ($query) => $query->where('marcas.id', $marcaId))
                        ->orderBy('nombre')
                        ->get()
                        ->mapWithKeys(fn (Mobiliario $mobiliario) => [
                            $mobiliario->id => filled($mobiliario->codigo_interno)
                                ? "{$mobiliario->codigo_interno} — {$mobiliario->nombre}"
                                : $mobiliario->nombre,
                        ]);
                })
                ->searchable()
                ->preload()
                ->required()
                ->disabled(fn (Get $get): bool => blank($get('marca_id'))),

            TextInput::make('cantidad')
                ->label('Cantidad')
                ->numeric()
                ->integer()
                ->minValue(1)
                ->required()
                ->default(1),

            Select::make('legajos')
                ->label('Empleado/s')
                ->multiple()
                ->default([])
                ->options(fn () => Empleado::query()->orderBy('nombre')->pluck('nombre', 'legajo'))
                ->searchable()
                ->preload()
                ->required()
                ->getOptionLabelsUsing(fn (array $values): array => Empleado::query()
                    ->whereIn('legajo', $values)
                    ->pluck('nombre', 'legajo')
                    ->all())
                ->createOptionModalHeading('Nuevo empleado')
                ->createOptionForm($this->empleadoFormSchema())
                ->createOptionUsing(function (array $data): string {
                    return Empleado::create($data)->legajo;
                }),
        ];
    }

    /**
     * @return array<int, TextInput>
     */
    protected function empleadoFormSchema(): array
    {
        return [
            TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
            TextInput::make('legajo')
                ->label('Legajo')
                ->required()
                ->maxLength(50)
                ->unique(table: 'empleados', column: 'legajo'),
        ];
    }

    /**
     * @param  array{marca_id: mixed, mobiliario_id: mixed, cantidad: mixed, legajos: mixed}  $data
     */
    protected function abrirEtiqueta(array $data): void
    {
        $url = route('impresora.etiqueta', [
            'marca_id' => $data['marca_id'],
            'mobiliario_id' => $data['mobiliario_id'],
            'cantidad' => $data['cantidad'],
            'legajos' => $data['legajos'],
        ]);

        $this->js('window.open('.json_encode($url).", '_blank')");

        Notification::make()
            ->success()
            ->title('Etiqueta enviada a impresión')
            ->send();
    }

    public function render()
    {
        return view('livewire.impresora-modal');
    }
}
