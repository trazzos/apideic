<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('proyectos')->name('proyectos.')->group(function()  {

    Route::get('', [App\Http\Controllers\ProyectoController::class,'list'])->name('list');
    Route::get('paginate', [App\Http\Controllers\ProyectoController::class,'paginate'])->name('paginate');
    Route::get('{proyecto}', [App\Http\Controllers\ProyectoController::class,'show'])->name('show');
    Route::get('{proyecto}/progreso', [App\Http\Controllers\ProyectoController::class,'getProgress'])->name('progress');
    Route::post('', [App\Http\Controllers\ProyectoController::class,'create'])->name('registrar');
    Route::patch('{proyecto}', [App\Http\Controllers\ProyectoController::class,'update'])->name('actualizar');
    Route::delete('{proyecto}', [App\Http\Controllers\ProyectoController::class,'delete'])->name('eliminar');


    Route::get('{proyecto}/actividades', [App\Http\Controllers\ActividadController::class,'list'])->name('actividades.list');
    Route::post('{proyecto}/actividades', [App\Http\Controllers\ActividadController::class,'create'])->name('actividades.create');
    Route::patch('{proyecto}/actividades/{actividad:uuid}', [App\Http\Controllers\ActividadController::class,'update'])->name('actividades.update');
    Route::delete('{proyecto}/actividades/{actividad:uuid}', [App\Http\Controllers\ActividadController::class,'delete'])->name('actividades.delete');
    Route::get('{proyecto}/actividades/{actividad:uuid}/progreso', [App\Http\Controllers\ActividadController::class,'getProgress'])->name('actividades.progress');

    Route::get('{proyecto}/actividades/{actividad}/tareas', [App\Http\Controllers\TareaController::class,'list'])->name('actividades.tareas.list');
    Route::post('{proyecto}/actividades/{actividad}/tareas', [App\Http\Controllers\TareaController::class,'create'])->name('actividades.tareas.create');
    Route::patch('{proyecto}/actividades/{actividad}/tareas/{tarea}', [App\Http\Controllers\TareaController::class,'update'])->name('actividades.tareas.update');
    Route::delete('{proyecto}/actividades/{actividad}/tareas/{tarea}', [App\Http\Controllers\TareaController::class,'delete'])->name('actividades.tareas.delete');
    Route::patch('{proyecto}/actividades/{actividad}/tareas/{tarea}/completar', [App\Http\Controllers\TareaController::class,'complete'])->name('actividades.tareas.complete');
    Route::patch('{proyecto}/actividades/{actividad}/tareas/{tarea}/pendiente', [App\Http\Controllers\TareaController::class,'markAsPending'])->name('actividades.tareas.pending');

    // Rutas para archivos de actividades
    Route::get('{proyecto}/actividades/{actividad}/archivos', [App\Http\Controllers\ArchivoController::class, 'list'])
        ->name('actividades.archivos.index');

    Route::post('{proyecto}/actividades/{actividad}/archivos', [App\Http\Controllers\ArchivoController::class, 'create'])
        ->name('actividades.archivos.store');

    Route::get('{proyecto}/actividades/{actividad}/archivos/{archivo}/descargar', [App\Http\Controllers\ArchivoController::class, 'download'])
        ->name('actividades.archivos.download');

    Route::delete('{proyecto}/actividades/{actividad}/archivos/{archivo::uuid}', [App\Http\Controllers\ArchivoController::class, 'delete'])
        ->name('actividades.archivos.delete');
});

