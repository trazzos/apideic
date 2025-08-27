<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

use App\Http\Requests\Organizacion\SecretariaPostRequest;
use App\Http\Requests\Organizacion\SecretariaPatchRequest;

use App\Services\SecretariaService;
use App\Models\Secretaria;


class SecretariaController extends BaseController
{
    public function __construct(
      private readonly SecretariaService $secretariaService
    )
    {
        //$this->middleware('permission:secretaria')->only(['lista']);
        //$this->middleware('permission:secretaria.crear')->only(['store']);
        //$this->middleware('permission:secretaria.editar')->only(['update']);
        //$this->middleware('permission:secretaria.eliminar')->only(['delete']);

    }


    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return $this->secretariaService->list();
    }

   /**
    * @param SecretariaPostRequest $request
    */
    public function create(SecretariaPostRequest $request): JsonResource
    {
        return $this->secretariaService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     * @param Secretaria $secretaria
     * @param SecretariaPatchRequest $request
     */
    public function update(Secretaria $secretaria, SecretariaPatchRequest $request): JsonResource
    {
        return $this->secretariaService->update($secretaria->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     * @param Secretaria $secretaria
     * @return Response
     * 
     */
    public function delete(Secretaria $secretaria): Response
    {
        return $this->secretariaService->delete($secretaria->id);
    }
}
