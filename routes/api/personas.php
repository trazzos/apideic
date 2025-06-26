<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('personas')->name('personas.')->group(function()  {

    Route::get('', [App\Http\Controllers\PersonaController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\PersonaController::class,'create'])->name('registrar');
    Route::patch('{persona}', [App\Http\Controllers\PersonaController::class,'update'])->name('actualizar');
    Route::delete('{persona}', [App\Http\Controllers\PersonaController::class,'delete'])->name('eliminar');

    //Route::put('{persona}/user', [App\Http\Controllers\PersonaController::class,'actualizarPassword'])->name('actualizar.user');
    //Route::delete('{persona}/user/{user}/desactivar', [App\Http\Controllers\PersonaController::class,'desactivarUsuario'])->name('desactivar.user');



});
