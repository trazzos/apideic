<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Beneficiario\BeneficiarioPostRequest;
use App\Http\Requests\Beneficiario\BeneficiarioPutRequest;
use App\Services\BeneficiarioService;
use App\Models\Beneficiario;


class BeneficiarioController extends BaseController
{
    public function __construct(
      private readonly BeneficiarioService $beneficiarioService
    )
    {

        //$this->middleware('permission:beneficiario')->only(['lista']);
        //$this->middleware('permission:beneficiario.crear')->only(['store']);
        //$this->middleware('permission:beneficiario.editar')->only(['update']);
        //$this->middleware('permission:beneficiario.eliminar')->only(['delete']);

    }


    public function list()
    {
        return $this->beneficiarioService->lista();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(BeneficiarioPostRequest $request)
    {
        return $this->beneficiarioService->crear($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Beneficiario $beneficiario, BeneficiarioPutRequest $request)
    {
        return $this->beneficiarioService->actualizar($request->validated(), $beneficiario->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Beneficiario $beneficiario)
    {
        return $this->beneficiarioService->eliminar($beneficiario->id);
    }
}
