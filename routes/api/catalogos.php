<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

Route::middleware(['auth:sanctum'])->prefix('autoridades')->name('autoridades.')->group(function()  {

    Route::get('', [App\Http\Controllers\AutoridadController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\AutoridadController::class,'create'])->name('registrar');
    Route::put('{autoridades}', [App\Http\Controllers\AutoridadController::class,'update'])->name('actualizar');
    Route::delete('{autoridades}', [App\Http\Controllers\AutoridadController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('beneficiarios')->name('beneficiarios.')->group(function()  {

    Route::get('', [App\Http\Controllers\BeneficiarioController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\BeneficiarioController::class,'create'])->name('registrar');
    Route::put('{beneficiarios}', [App\Http\Controllers\BeneficiarioController::class,'update'])->name('actualizar');
    Route::delete('{beneficiarios}', [App\Http\Controllers\BeneficiarioController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('capacitadores')->name('capacitadores.')->group(function()  {

    Route::get('', [App\Http\Controllers\CapacitadorController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\CapacitadorController::class,'create'])->name('registrar');
    Route::put('{capacitadores}', [App\Http\Controllers\CapacitadorController::class,'update'])->name('actualizar');
    Route::delete('{capacitadores}', [App\Http\Controllers\CapacitadorController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('departamentos')->name('departamentos.')->group(function()  {

    Route::get('', [App\Http\Controllers\DepartamentoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\DepartamentoController::class,'create'])->name('registrar');
    Route::put('{departamento}', [App\Http\Controllers\DepartamentoController::class,'update'])->name('actualizar');
    Route::delete('{departamento}', [App\Http\Controllers\DepartamentoController::class,'delete'])->name('eliminar');
});


Route::middleware(['auth:sanctum'])->prefix('tipos-actividad')->name('tipos-actividad.')->group(function()  {

    Route::get('', [App\Http\Controllers\TipoActividadController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\TipoActividadController::class,'create'])->name('registrar');
    Route::put('{tiposActividad}', [App\Http\Controllers\TipoActividadController::class,'update'])->name('actualizar');
    Route::delete('{tiposActividad}', [App\Http\Controllers\TipoActividadController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('tipos-documento')->name('tipos-documento.')->group(function()  {

    Route::get('', [App\Http\Controllers\TipoDocumentoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\TipoDocumentoController::class,'create'])->name('registrar');
    Route::put('{tiposDocumento}', [App\Http\Controllers\TipoDocumentoController::class,'update'])->name('actualizar');
    Route::delete('{tiposDocumento}', [App\Http\Controllers\TipoDocumentoController::class,'delete'])->name('eliminar');
});


Route::middleware(['auth:sanctum'])->prefix('tipos-proyecto')->name('tipos-proyecto.')->group(function()  {

    Route::get('', [App\Http\Controllers\TipoProyectoController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\TipoProyectoController::class,'create'])->name('registrar');
    Route::put('{tiposProyecto}', [App\Http\Controllers\TipoProyectoController::class,'update'])->name('actualizar');
    Route::delete('{tiposProyecto}', [App\Http\Controllers\TipoProyectoController::class,'delete'])->name('eliminar');
});


Route::middleware(['auth:sanctum'])->prefix('roles')->name('roles.')->group(function()  {

    Route::get('', [App\Http\Controllers\RoleController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\RoleController::class,'create'])->name('registrar');
    Route::put('{roles}', [App\Http\Controllers\RoleController::class,'update'])->name('actualizar');
    Route::delete('{roles}', [App\Http\Controllers\RoleController::class,'delete'])->name('eliminar');
});

Route::middleware([StartSession::class])->get('debug-session', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'session_id' => $request->session()->getId(),
        'all_session_data' => $request->session()->all(),
        'user' => $request->user(),
        'cookies' => $request->cookies->all(),
    ]);
});








