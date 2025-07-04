<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('proyectos')->name('proyectos.')->group(function()  {

    Route::get('', [App\Http\Controllers\ProyectoController::class,'list'])->name('list');
    Route::get('paginate', [App\Http\Controllers\ProyectoController::class,'paginate'])->name('paginate');
    Route::get('{proyecto:uuid}', [App\Http\Controllers\ProyectoController::class,'show'])->name('show');
    Route::post('', [App\Http\Controllers\ProyectoController::class,'create'])->name('registrar');
    Route::patch('{proyecto:uuid}', [App\Http\Controllers\ProyectoController::class,'update'])->name('actualizar');
    Route::delete('{proyecto:uuid}', [App\Http\Controllers\ProyectoController::class,'delete'])->name('eliminar');


    Route::get('{proyecto:uuid}/actividades', [App\Http\Controllers\ActividadController::class,'list'])->name('actividades.list');
    Route::post('{proyecto:uuid}/actividades', [App\Http\Controllers\ActividadController::class,'create'])->name('actividades.create');
    Route::patch('actividades/{actividad:uuid}', [App\Http\Controllers\ActividadController::class,'update'])->name('actividades.update');
    Route::delete('actividades/{actividad:uuid}', [App\Http\Controllers\ActividadController::class,'delete'])->name('actividades.delete');

    Route::get('{proyecto}/actividades/{actividad}/tareas', [App\Http\Controllers\TareaController::class,'list'])->name('actividades.tareas.list');
    Route::post('{proyecto}/actividades/{actividad}/tareas', [App\Http\Controllers\TareaController::class,'create'])->name('actividades.tareas.create');
    Route::patch('{proyecto}/actividades/{actividad}/tareas/{tarea}', [App\Http\Controllers\TareaController::class,'update'])->name('actividades.tareas.update');
    Route::delete('{proyecto}/actividades/{actividad}/tareas/{tarea}', [App\Http\Controllers\TareaController::class,'delete'])->name('actividades.tareas.delete');
    Route::patch('{proyecto}/actividades/{actividad}/tareas/{tarea}/completar', [App\Http\Controllers\TareaController::class,'complete'])->name('actividades.tareas.complete');
    Route::patch('{proyecto}/actividades/{actividad}/tareas/{tarea}/pendiente', [App\Http\Controllers\TareaController::class,'markAsPending'])->name('actividades.tareas.pending');
});

