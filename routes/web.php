<?php

use App\Http\Controllers\ImpresoraEtiquetaController;
use App\Http\Controllers\InsumoSillasPdfController;
use App\Http\Controllers\MobiliarioPendientesEntregaPdfController;
use App\Http\Controllers\PresupuestoPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/presupuesto/{presupuesto}/pdf', [PresupuestoPdfController::class, 'show'])
        ->name('presupuesto.pdf');

    Route::get('/presupuesto/{presupuesto}/pdf/ver', [PresupuestoPdfController::class, 'viewer'])
        ->name('presupuesto.pdf.viewer');

    Route::get('/presupuesto/{presupuesto}/excel', [PresupuestoPdfController::class, 'excel'])
        ->name('presupuesto.excel');

    Route::get('/presupuesto/{presupuesto}/produccion', [PresupuestoPdfController::class, 'produccionPdf'])
        ->name('presupuesto.produccion.pdf');

    Route::get('/presupuesto/{presupuesto}/produccion/ver', [PresupuestoPdfController::class, 'produccionViewer'])
        ->name('presupuesto.produccion.viewer');

    Route::get('/presupuesto/{presupuesto}/produccion/excel', [PresupuestoPdfController::class, 'produccionExcel'])
        ->name('presupuesto.produccion.excel');

    Route::get('/presupuesto/{presupuesto}/layout', [PresupuestoPdfController::class, 'layout'])
        ->name('presupuesto.layout');

    Route::get('/mobiliarios/pendientes-entrega/pdf', MobiliarioPendientesEntregaPdfController::class)
        ->name('mobiliarios.pendientes-entrega.pdf');

    Route::get('/insumos/sillas/pdf', InsumoSillasPdfController::class)
        ->name('insumos.sillas.pdf');

    Route::get('/impresora/etiqueta', ImpresoraEtiquetaController::class)
        ->name('impresora.etiqueta');
});
