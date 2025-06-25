<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;


use App\Models\TipoProyecto;
use App\Services\TipoProyectoService;
use App\Http\Requests\TipoProyecto\TipoProyectoPostRequest;
use App\Http\Requests\TipoProyecto\TipoProyectoPutRequest;
class TipoProyectoController extends BaseController
{
    public function __construct(
      private readonly TipoProyectoService $tipoProyectoService
    )
    {

        //$this->middleware('permission:tipo_proyecto')->only(['lista']);
        //$this->middleware('permission:tipo_proyecto.crear')->only(['create']);
        //$this->middleware('permission:tipo_proyecto.editar')->only(['update']);
        //$this->middleware('permission:tipo_proyecto.editar')->only(['delete']);

    }


    public function list()
    {
        return $this->tipoProyectoService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(TipoProyectoPostRequest $request)
    {
        return $this->tipoProyectoService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TipoProyecto $tiposProyecto, TipoProyectoPutRequest $request)
    {
        return $this->tipoProyectoService->update($tiposProyecto->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(TipoProyecto $tiposProyecto)
    {
        return $this->tipoProyectoService->delete($tiposProyecto->id);
    }
}
