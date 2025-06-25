<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Capacitador\CapacitadorPostRequest;
use App\Http\Requests\Capacitador\CapacitadorPutRequest;
use App\Services\CapacitadorService;
use App\Models\Capacitador;


class CapacitadorController extends BaseController
{
    public function __construct(
      private readonly CapacitadorService $capacitadorService
    )
    {

        //$this->middleware('permission:capacitador')->only(['lista']);
        //$this->middleware('permission:capacitador.crear')->only(['store']);
        //$this->middleware('permission:capacitador.editar')->only(['update']);
        //$this->middleware('permission:capacitador.eliminar')->only(['delete']);

    }


    public function list()
    {
        return $this->capacitadorService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(CapacitadorPostRequest $request)
    {
        return $this->capacitadorService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Capacitador $capacitadores, CapacitadorPutRequest $request)
    {
        return $this->capacitadorService->update($request->validated(), $capacitadores->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Capacitador $capacitadores)
    {
        return $this->capacitadorService->delete($capacitadores->id);
    }
}
