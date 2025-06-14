<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Departamento\DepartamentoPostRequest;
use App\Http\Requests\Departamento\DepartamentoPutRequest;
use App\Services\DepartamentoService;
use App\Models\Departamento;


class DepartamentoController extends BaseController
{
    public function __construct(
      private readonly DepartamentoService $departamentoService
    )
    {

        //$this->middleware('permission:departamento')->only(['lista']);
        //$this->middleware('permission:departamento.crear')->only(['store']);
        //$this->middleware('permission:departamento.editar')->only(['update']);
        //$this->middleware('permission:departamento.eliminar')->only(['delete']);

    }


    public function list()
    {
        return $this->departamentoService->lista();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(DepartamentoPostRequest $request)
    {
        return $this->departamentoService->crear($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Departamento $departamento, DepartamentoPutRequest $request)
    {
        return $this->departamentoService->actualizar($departamento->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Departamento $departamento)
    {
        return $this->departamentoService->eliminar($departamento->id);
    }
}
