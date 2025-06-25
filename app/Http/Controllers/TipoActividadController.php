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
        return $this->tipoActividadService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(TipoActividadPostRequest $request)
    {
        return $this->tipoActividadService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TipoActividad $tiposActividad, TipoActividadPutRequest $request)
    {
        return $this->tipoActividadService->update($tiposActividad->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(TipoActividad $tiposActividad)
    {
        return $this->tipoActividadService->delete($tiposActividad->id);
    }
}
