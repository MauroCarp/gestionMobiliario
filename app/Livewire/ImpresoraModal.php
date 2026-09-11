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
            ->action(function (array $data): void {
                if ((int) $data['cantidad'] > 10) {
                    $this->datosPendientes = $data;
                    $this->replaceMountedAction('confirmarCantidad');

                    return;
                }

                $this->abrirEtiqueta($data);
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
                $this->datosPendientes = [];
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

            Select::make('legajo')
                ->label('Empleado')
                ->options(fn () => Empleado::query()->orderBy('nombre')->pluck('nombre', 'legajo'))
                ->searchable()
                ->preload()
                ->required()
                ->getOptionLabelUsing(fn ($value): ?string => Empleado::query()->where('legajo', $value)->value('nombre'))
                ->getSelectedRecordUsing(fn ($state): ?Empleado => filled($state)
                    ? Empleado::query()->where('legajo', $state)->first()
                    : null)
                ->createOptionModalHeading('Nuevo empleado')
                ->createOptionForm($this->empleadoFormSchema())
                ->createOptionUsing(function (array $data): string {
                    return Empleado::create($data)->legajo;
                })
                ->editOptionModalHeading('Editar empleado')
                ->editOptionForm($this->empleadoFormSchema(ignorarLegajoActual: true))
                ->fillEditOptionActionFormUsing(fn (Select $component): array => $component->getSelectedRecord()?->only(['nombre', 'legajo']) ?? [])
                ->updateOptionUsing(function (array $data, Select $component): void {
                    $empleado = $component->getSelectedRecord();

                    if (! $empleado instanceof Empleado) {
                        return;
                    }

                    $empleado->update($data);
                    $component->state($data['legajo']);
                }),
        ];
    }

    /**
     * @return array<int, TextInput>
     */
    protected function empleadoFormSchema(bool $ignorarLegajoActual = false): array
    {
        $legajo = TextInput::make('legajo')
            ->label('Legajo')
            ->required()
            ->maxLength(50);

        if ($ignorarLegajoActual) {
            $legajo->unique(table: 'empleados', column: 'legajo', ignoreRecord: true);
        } else {
            $legajo->unique(table: 'empleados', column: 'legajo');
        }

        return [
            TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
            $legajo,
        ];
    }

    /**
     * @param  array{marca_id: mixed, mobiliario_id: mixed, cantidad: mixed, legajo: mixed}  $data
     */
    protected function abrirEtiqueta(array $data): void
    {
        $url = route('impresora.etiqueta', [
            'marca_id' => $data['marca_id'],
            'mobiliario_id' => $data['mobiliario_id'],
            'cantidad' => $data['cantidad'],
            'legajo' => $data['legajo'],
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
