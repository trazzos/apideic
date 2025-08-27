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
    Route::get('sin-permisos', [App\Http\Controllers\RoleController::class,'listSinPermiso'])->name('lista-sin-permiso');
    Route::post('', [App\Http\Controllers\RoleController::class,'create'])->name('registrar');
    Route::put('{role}', [App\Http\Controllers\RoleController::class,'update'])->name('actualizar');
    Route::delete('{role}', [App\Http\Controllers\RoleController::class,'delete'])->name('eliminar');
});

Route::middleware(['auth:sanctum'])->prefix('secretarias')->name('secretarias.')->group(function()  {

    Route::get('', [App\Http\Controllers\SecretariaController::class,'list'])->name('lista');
    Route::post('', [App\Http\Controllers\SecretariaController::class,'create'])->name('registrar');
    Route::patch('{secretaria}', [App\Http\Controllers\SecretariaController::class,'update'])->name('actualizar');
    Route::delete('{secretaria}', [App\Http\Controllers\SecretariaController::class,'delete'])->name('eliminar');

    Route::get('{secretaria}/subsecretarias', [App\Http\Controllers\SubsecretariaController::class,'list'])->name('subsecretarias.lista');
    Route::post('{secretaria}/subsecretarias', [App\Http\Controllers\SubsecretariaController::class,'create'])->name('subsecretarias.registrar');
    Route::patch('{secretaria}/subsecretarias/{subsecretaria}', [App\Http\Controllers\SubsecretariaController::class,'update'])->name('subsecretarias.actualizar');
    Route::delete('{secretaria}/subsecretarias/{subsecretaria}', [App\Http\Controllers\SubsecretariaController::class,'delete'])->name('subsecretarias.eliminar');

    Route::get('{secretaria}/subsecretarias/{subsecretaria}/direcciones', [App\Http\Controllers\DireccionController::class,'list'])->name('subsecretarias.direcciones.lista');
    Route::post('{secretaria}/subsecretarias/{subsecretaria}/direcciones', [App\Http\Controllers\DireccionController::class,'create'])->name('subsecretarias.direcciones.registrar');
    Route::patch('{secretaria}/subsecretarias/{subsecretaria}/direcciones/{direccion}', [App\Http\Controllers\DireccionController::class,'update'])->name('subsecretarias.direcciones.actualizar');
    Route::delete('{secretaria}/subsecretarias/{subsecretaria}/direcciones/{direccion}', [App\Http\Controllers\DireccionController::class,'delete'])->name('subsecretarias.direcciones.eliminar');
});
 Route::get('subsecretarias', [App\Http\Controllers\SubsecretariaController::class,'list'])
        ->middleware(['auth:sanctum'])
        ->name('subsecretarias.lista');

 Route::get('direcciones', [App\Http\Controllers\DireccionController::class,'list'])
        ->middleware(['auth:sanctum'])
        ->name('direcciones.lista');       

// Endpoint consolidado para todos los catálogos
Route::middleware(['auth:sanctum'])->prefix('catalogos')->name('catalogos.')->group(function()  {
    Route::get('all', [App\Http\Controllers\CatalogoController::class,'all'])->name('all');
});



Route::middleware([StartSession::class])->get('debug-session', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'session_id' => $request->session()->getId(),
        'all_session_data' => $request->session()->all(),
        'user' => $request->user(),
        'cookies' => $request->cookies->all(),
    ]);
});








