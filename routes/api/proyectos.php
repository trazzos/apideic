<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('proyectos')->name('proyectos.')->group(function()  {

    Route::get('', [App\Http\Controllers\ProyectoController::class,'list'])->name('list');
    Route::get('paginate', [App\Http\Controllers\ProyectoController::class,'paginate'])->name('paginate');
    Route::get('{proyecto:uuid}', [App\Http\Controllers\ProyectoController::class,'show'])->name('show');
    Route::post('', [App\Http\Controllers\ProyectoController::class,'create'])->name('registrar');
    Route::patch('{proyecto:uuid}', [App\Http\Controllers\ProyectoController::class,'update'])->name('actualizar');
    Route::delete('{proyecto:uuid}', [App\Http\Controllers\ProyectoController::class,'delete'])->name('eliminar');


    Route::get('{proyecto:uuid}/actividades', [App\Http\Controllers\ActividadController::class,'list'])->name('actividades.lista');
    Route::post('{proyecto:uuid}/actividades', [App\Http\Controllers\ActividadController::class,'create'])->name('actividades.registrar');
    Route::patch('actividades/{actividad:uuid}', [App\Http\Controllers\ActividadController::class,'update'])->name('actividades.actualizar');
    Route::delete('actividades/{actividad:uuid}', [App\Http\Controllers\ActividadController::class,'delete'])->name('actividades.eliminar');
});

