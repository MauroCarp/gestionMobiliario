<?php

namespace App\Filament\Forms;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use Filament\Forms;
use Filament\Forms\Get;

class PresupuestoEntregaParcialForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Repeater::make('items')
                ->label('Ítems')
                ->schema([
                    Forms\Components\Hidden::make('id'),
                    Forms\Components\Hidden::make('label'),
                    Forms\Components\Hidden::make('pendiente'),
                    Forms\Components\Hidden::make('puede_entregar'),
                    Forms\Components\TextInput::make('cantidad')
                        ->label('Total')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('cantidad_entregada')
                        ->label('Entregado')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('pendiente_visible')
                        ->label('Pendiente')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('cantidad_a_entregar')
                        ->label('Cantidad a entregar')
                        ->numeric()
                        ->integer()
                        ->required()
                        ->minValue(0)
                        ->maxValue(fn (Get $get): int => (int) $get('pendiente'))
                        ->disabled(fn (Get $get): bool => ! $get('puede_entregar') || (int) $get('pendiente') <= 0)
                        ->dehydrated()
                        ->helperText(function (Get $get): ?string {
                            if ((int) $get('pendiente') <= 0) {
                                return 'Este ítem ya fue entregado por completo.';
                            }

                            if (! $get('puede_entregar')) {
                                return 'Debe finalizarse la producción del lote antes de entregar.';
                            }

                            return 'Por defecto se propone el saldo pendiente. No puede superar ese valor.';
                        }),
                ])
                ->columns(4)
                ->addable(false)
                ->deletable(false)
                ->reorderable(false)
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['label'] . ' [' . $state['codigo_interno'] . ']' ?? null)
                ->collapsed(true),
        ];
    }

    /**
     * @return array{items: list<array<string, mixed>>}
     */
    public static function state(Presupuesto $presupuesto): array
    {
        return [
            'items' => $presupuesto->items()
                ->orderBy('orden')
                ->get()
                ->map(fn (PresupuestoItem $item): array => [
                    'id'                   => $item->id,
                    'codigo_interno'       => $item->item_codigo,
                    'label'                => $item->item_nombre,
                    'cantidad'             => $item->cantidad,
                    'cantidad_entregada'   => $item->cantidad_entregada,
                    'pendiente'            => $item->cantidadPendiente(),
                    'pendiente_visible'    => $item->cantidadPendiente(),
                    'puede_entregar'       => $item->puedeRecibirEntrega(),
                    'cantidad_a_entregar'  => $item->puedeRecibirEntrega() ? $item->cantidadPendiente() : 0,
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, int>
     */
    public static function cantidades(array $data): array
    {
        $cantidades = [];

        foreach ($data['items'] ?? [] as $row) {
            $id = (int) ($row['id'] ?? 0);
            $cantidad = (int) ($row['cantidad_a_entregar'] ?? 0);

            if ($id > 0 && $cantidad > 0) {
                $cantidades[$id] = $cantidad;
            }
        }

        return $cantidades;
    }
}
