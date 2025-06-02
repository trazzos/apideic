<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\TipoActividad\TipoActividadPostRequest;
use App\Http\Requests\TipoActividad\TipoActividadPutRequest;
use App\Services\TipoActividadService;
use App\Models\TipoActividad;


class TipoActividadController extends BaseController
{
    public function __construct(
      private readonly TipoActividadService $tipoActividadService
    )
    {

        //$this->middleware('permission:tipo_actividad')->only(['lista']);
        //$this->middleware('permission:tipo_actividad.crear')->only(['store']);
        //$this->middleware('permission:tipo_actividad.editar')->only(['update']);
        //$this->middleware('permission:tipo_actividad.eliminar')->only(['delete']);

    }


    public function list()
    {
        return $this->tipoActividadService->lista();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(TipoActividadPostRequest $request)
    {
        return $this->tipoActividadService->crear($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TipoActividad $tipoActividad, TipoActividadPutRequest $request)
    {
        return $this->tipoActividadService->actualizar($request->validated(), $tipoActividad->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(TipoActividad $tipoActividad)
    {
        return $this->tipoActividadService->eliminar($tipoActividad->id);
    }
}
