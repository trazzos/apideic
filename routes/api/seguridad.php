<?php
use Illuminate\Support\Facades\Route;
Route::prefix('auth')->name('auth.')->group(function()  {

    Route::middleware(['web'])->post('login', App\Http\Controllers\Auth\LoginController::class)->name('login');
    Route::middleware(['web'])->post('logout', App\Http\Controllers\Auth\LogoutController::class)
        ->name('logout')
        ->middleware(['auth:sanctum']);

    Route::get('profile', App\Http\Controllers\Auth\PerfilController::class)
        ->name('profile')
        ->middleware('auth:sanctum');
});

Route::get('/permisos', \App\Http\Controllers\PermisoController::class)
    ->name('permisos.lista')
    ->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->prefix('user')->name('user.')->group(function()  {

    Route::get('', [App\Http\Controllers\UserController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\UserController::class,'create'])->name('registrar');
    Route::put('{user}', [App\Http\Controllers\UserController::class,'update'])->name('actualizar');
    Route::delete('{user}', [App\Http\Controllers\UserController::class,'delete'])->name('eliminar');
});
