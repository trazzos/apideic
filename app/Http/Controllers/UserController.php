<?php

namespace App\Http\Controllers;

use App\Services\TipoActividadService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function __construct(
      private readonly TipoActividadService $tipoActividadService
    )
    {

        //$this->middleware('permission:tipo_actividad')->only(['lista']);
        //$this->middleware('permission:tipo_actividad.crear')->only(['store']);
        //$this->middleware('permission:tipo_actividad.editar')->only(['update']);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function lista()
    {
        return $this->tipoActividadService->lista();
    }

    public function fila(int $id)
    {
        return $this->tipoActividadService->find($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function crear(Request $request)
    {
        return $this->tipoActividadService->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function actualizar(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eliminar(string $id)
    {
        $this->tipoActividadService->eliminar($id);
    }
}
