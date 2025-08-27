<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Requests\Organizacion\DireccionPostRequest;
use App\Http\Requests\Organizacion\DireccionPatchRequest;
use App\Services\DireccionService;
use App\Models\Subsecretaria;
use App\Models\Secretaria;
use App\Models\Direccion;
use App\Services\Search\SearchCriteria;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class DireccionController extends BaseController
{
    public function __construct(
      private readonly DireccionService $direccionService
    )
    {
        //$this->middleware('permission:autoridad')->only(['lista']);
        //$this->middleware('permission:autoridad.crear')->only(['store']);
        //$this->middleware('permission:autoridad.editar')->only(['update']);
        //$this->middleware('permission:autoridad.eliminar')->only(['delete']);

    }

    /**
     * Display a listing of the resource.
     *
     * @param Secretaria $secretaria
     * @param Subsecretaria|null $subsecretaria
     * @return ResourceCollection
     */
    public function list(Secretaria $secretaria, ?Subsecretaria $subsecretaria): ResourceCollection
    {
        if ($subsecretaria) {
            $criteria =  new SearchCriteria();
            $criteria->addFilter('subsecretaria_id', $subsecretaria->id);
            return $this->direccionService->search($criteria);
        }
        return $this->direccionService->list();
    }

   /**
    * Create a new resource.
    *
    * @param Secretaria $secretaria
    * @param Subsecretaria $subsecretaria
    * @param DireccionPostRequest $request
    * @return JsonResource
    */
    public function create(Secretaria $secretaria,Subsecretaria $subsecretaria, DireccionPostRequest $request): JsonResource
    {
        $data = $request->validated();
        $data['subsecretaria_id'] = $subsecretaria->id;

        return $this->direccionService->create($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Secretaria $secretaria
     * @param Subsecretaria $subsecretaria
     * @param Direccion $direccion
     * @param DireccionPatchRequest $request
     * @return JsonResource
     */
    public function update(Secretaria $secretaria, Subsecretaria $subsecretaria, Direccion $direccion, DireccionPatchRequest $request): JsonResource
    {
        return $this->direccionService->update($direccion->id, $request->validated());
    }

    /**
     * Remove the specified resource.
     *
     * @param Secretaria $secretaria
     * @param Subsecretaria $subsecretaria
     * @param Direccion $direccion
     * @return Response
     */
    public function delete(Secretaria $secretaria, Subsecretaria $subsecretaria, Direccion $direccion): Response
    {
        return $this->direccionService->delete($direccion->id);
    }
}
