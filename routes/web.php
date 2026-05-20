<?php

use App\Http\Controllers\PresupuestoPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/presupuesto/{presupuesto}/pdf', [PresupuestoPdfController::class, 'show'])
        ->name('presupuesto.pdf');

    Route::get('/presupuesto/{presupuesto}/excel', [PresupuestoPdfController::class, 'excel'])
        ->name('presupuesto.excel');
});
