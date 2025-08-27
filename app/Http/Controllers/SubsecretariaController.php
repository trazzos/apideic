<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

use App\Http\Requests\Organizacion\SubsecretariaPostRequest;
use App\Http\Requests\Organizacion\SubsecretariaPatchRequest;

use App\Services\Search\SearchCriteria;

use App\Services\SubsecretariaService;
use App\Models\Subsecretaria;
use App\Models\Secretaria;

class SubsecretariaController extends BaseController
{
    public function __construct(
      private readonly SubsecretariaService $subsecretariaService
    )
    {
        //$this->middleware('permission:subsecretaria')->only(['lista']);
        //$this->middleware('permission:subsecretaria.crear')->only(['store']);
        //$this->middleware('permission:subsecretaria.editar')->only(['update']);
        //$this->middleware('permission:subsecretaria.eliminar')->only(['delete']);

    }


    /**
     * Display a listing of the resource.
     * @param Secretaria|null $secretaria
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
       return $this->subsecretariaService->list();
    }

   /**
    * @param SubsecretariaPostRequest $request
    * @return JsonResource
    */
    public function create(SubsecretariaPostRequest $request): JsonResource
    {
        $data = $request->validated();
        return $this->subsecretariaService->create($data);
    }


    /**
     * Update the specified resource in storage.
     * @param Subsecretaria $subsecretaria
     * @return JsonResource
     */
    public function update(Subsecretaria $subsecretaria, SubsecretariaPatchRequest $request): JsonResource
    {
        return $this->subsecretariaService->update($subsecretaria->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     * @param Subsecretaria $subsecretaria
     * @return Response
     */
    public function delete(Subsecretaria $subsecretaria): Response
    {
        return $this->subsecretariaService->delete($subsecretaria->id);
    }
}
