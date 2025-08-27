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
    public function list(?Secretaria $secretaria = null): ResourceCollection
    {
        if ($secretaria) {
            $criteria =  new SearchCriteria();
            $criteria->addFilter('secretaria_id', $secretaria->id);
            return $this->subsecretariaService->search($criteria);
        }
        return $this->subsecretariaService->list();
    }

   /**
    * @param Secretaria $secretaria
    * @param SubsecretariaPostRequest $request
    * @return JsonResource
    */
    public function create(Secretaria $secretaria, SubsecretariaPostRequest $request): JsonResource
    {
        $data = $request->validated();
        $data['secretaria_id'] = $secretaria->id;

        return $this->subsecretariaService->create($data);
    }


    /**
     * Update the specified resource in storage.
     * @param Secretaria $secretaria
     * @param Subsecretaria $subsecretaria
     * @return JsonResource
     */
    public function update(Secretaria $secretaria, Subsecretaria $subsecretaria, SubsecretariaPatchRequest $request): JsonResource
    {
        return $this->subsecretariaService->update($subsecretaria->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     * @param Secretaria $secretaria
     * @param Subsecretaria $subsecretaria
     * @return Response
     */
    public function delete(Secretaria $secretaria, Subsecretaria $subsecretaria): Response
    {
        return $this->subsecretariaService->delete($subsecretaria->id);
    }
}
