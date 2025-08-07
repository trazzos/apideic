<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('personas')->name('personas.')->group(function()  {

    Route::get('', [App\Http\Controllers\PersonaController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\PersonaController::class,'create'])->name('registrar');
    Route::patch('{persona}', [App\Http\Controllers\PersonaController::class,'update'])->name('actualizar');
    Route::delete('{persona}', [App\Http\Controllers\PersonaController::class,'delete'])->name('eliminar');

    Route::get('{persona}/cuenta', [App\Http\Controllers\PersonaController::class,'infoCuenta'])->name('info.cuenta');
    Route::post('{persona}/cuenta', [App\Http\Controllers\PersonaController::class,'actualizarCuenta'])->name('actualizar.cuenta');
    Route::delete('{persona}/desactivar-cuenta', [App\Http\Controllers\PersonaController::class,'desactivarCuenta'])->name('desactivar.cuenta');
});
