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
        return $this->beneficiarioService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(BeneficiarioPostRequest $request)
    {
        return $this->beneficiarioService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Beneficiario $beneficiarios, BeneficiarioPutRequest $request)
    {
        return $this->beneficiarioService->update($beneficiarios->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Beneficiario $beneficiarios)
    {
        return $this->beneficiarioService->delete($beneficiarios->id);
    }
}
