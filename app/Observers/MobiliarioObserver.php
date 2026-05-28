<?php

namespace App\Observers;

use App\Models\Mobiliario;
use App\Models\MobiliarioHistorialPrecio;

class MobiliarioObserver
{
    public function created(Mobiliario $mobiliario): void
    {
        if ($mobiliario->precio !== null) {
            MobiliarioHistorialPrecio::create([
                'mobiliario_id' => $mobiliario->id,
                'user_id'       => auth()->id(),
                'precio'        => $mobiliario->precio,
            ]);
        }
    }

    public function updating(Mobiliario $mobiliario): void
    {
        if ($mobiliario->isDirty('precio') && $mobiliario->precio !== null) {
            MobiliarioHistorialPrecio::create([
                'mobiliario_id' => $mobiliario->id,
                'user_id'       => auth()->id(),
                'precio'        => $mobiliario->precio,
            ]);
        }
    }
}
