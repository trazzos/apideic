<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('reportes')->name('reportes.')->group(function()  {

    Route::post('actividades-gb-tipo-proyecto', [App\Http\Controllers\ReporteController::class,'actividadesPorTipoProyecto'])->name('actividades');
    Route::get('actividades', [App\Http\Controllers\ReporteController::class,'buscarActividades'])->name('actividades.buscar');
});
