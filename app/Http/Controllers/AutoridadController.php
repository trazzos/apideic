<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Autoridad\AutoridadPostRequest;
use App\Http\Requests\Autoridad\AutoridadPutRequest;
use App\Services\AutoridadService;
use App\Models\Autoridad;


class AutoridadController extends BaseController
{
    public function __construct(
      private readonly AutoridadService $autoridadService
    )
    {
        //$this->middleware('permission:autoridad')->only(['lista']);
        //$this->middleware('permission:autoridad.crear')->only(['store']);
        //$this->middleware('permission:autoridad.editar')->only(['update']);
        //$this->middleware('permission:autoridad.eliminar')->only(['delete']);

    }


    public function list()
    {
        return $this->autoridadService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(AutoridadPostRequest $request)
    {
        return $this->autoridadService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Autoridad $autoridades, AutoridadPutRequest $request)
    {
        return $this->autoridadService->update($autoridades->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Autoridad $autoridades)
    {
        return $this->autoridadService->delete($autoridades->id);
    }
}
