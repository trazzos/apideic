<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('autoridad')->name('autoridad.')->group(function()  {

    Route::get('', [App\Http\Controllers\AutoridadController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\AutoridadController::class,'create'])->name('registrar');
    Route::put('{autoridad}', [App\Http\Controllers\AutoridadController::class,'update'])->name('actualizar');
    Route::delete('{autoridad}', [App\Http\Controllers\AutoridadController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('beneficiario')->name('beneficiario.')->group(function()  {

    Route::get('', [App\Http\Controllers\BeneficiarioController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\BeneficiarioController::class,'create'])->name('registrar');
    Route::put('{beneficiario}', [App\Http\Controllers\BeneficiarioController::class,'update'])->name('actualizar');
    Route::delete('{beneficiario}', [App\Http\Controllers\BeneficiarioController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('capacitador')->name('capacitador.')->group(function()  {

    Route::get('', [App\Http\Controllers\CapacitadorController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\CapacitadorController::class,'create'])->name('registrar');
    Route::put('{capacitador}', [App\Http\Controllers\CapacitadorController::class,'update'])->name('actualizar');
    Route::delete('{capacitador}', [App\Http\Controllers\CapacitadorController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('departamento')->name('departamento.')->group(function()  {

    Route::get('', [App\Http\Controllers\DepartamentoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\DepartamentoController::class,'create'])->name('registrar');
    Route::put('{departamento}', [App\Http\Controllers\DepartamentoController::class,'update'])->name('actualizar');
    Route::delete('{departamento}', [App\Http\Controllers\DepartamentoController::class,'delete'])->name('eliminar');
});


Route::middleware(['auth:sanctum'])->prefix('tipo-actividad')->name('tipo_actividad.')->group(function()  {

    Route::get('', [App\Http\Controllers\TipoActividadController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\TipoActividadController::class,'create'])->name('registrar');
    Route::put('{$id}', [App\Http\Controllers\TipoActividadController::class,'update'])->name('actualizar');
    Route::delete('{id}', [App\Http\Controllers\TipoActividadController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('tipo-documento')->name('tipo-documento.')->group(function()  {

    Route::get('', [App\Http\Controllers\TipoDocumentoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\TipoDocumentoController::class,'create'])->name('registrar');
    Route::put('{tipoDocumento}', [App\Http\Controllers\TipoDocumentoController::class,'update'])->name('actualizar');
    Route::delete('{tipoDocumento}', [App\Http\Controllers\TipoDocumentoController::class,'delete'])->name('eliminar');
});


Route::middleware(['auth:sanctum'])->prefix('tipo-proyecto')->name('tipo-proyecto.')->group(function()  {

    Route::get('', [App\Http\Controllers\TipoProyectoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\TipoProyectoController::class,'create'])->name('registrar');
    Route::put('{tipoProyecto}', [App\Http\Controllers\TipoProyectoController::class,'update'])->name('actualizar');
    Route::delete('{tipoProyecto}', [App\Http\Controllers\TipoProyectoController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('proyecto')->name('proyecto.')->group(function()  {

    Route::get('', [App\Http\Controllers\ProyectoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\ProyectoController::class,'create'])->name('registrar');
    Route::put('{proyecto}', [App\Http\Controllers\ProyectoController::class,'update'])->name('actualizar');
    Route::delete('{proyecto}', [App\Http\Controllers\ProyectoController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('role')->name('role.')->group(function()  {

    Route::get('', [App\Http\Controllers\RoleController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\RoleController::class,'create'])->name('registrar');
    Route::put('{role}', [App\Http\Controllers\RoleController::class,'update'])->name('actualizar');
    Route::delete('{role}', [App\Http\Controllers\RoleController::class,'delete'])->name('actualizar');
});








