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
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return $this->direccionService->list();
    }

   /**
    * Create a new resource
    * @param DireccionPostRequest $request
    * @return JsonResource
    */
    public function create(DireccionPostRequest $request): JsonResource
    {
        return $this->direccionService->create($request->validated());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Direccion $direccion
     * @param DireccionPatchRequest $request
     * @return JsonResource
     */
    public function update(Direccion $direccion, DireccionPatchRequest $request): JsonResource
    {
        return $this->direccionService->update($direccion->id, $request->validated());
    }

    /**
     * Remove the specified resource.
     *
     * @param Direccion $direccion
     * @return Response
     */
    public function delete(Direccion $direccion): Response
    {
        return $this->direccionService->delete($direccion->id);
    }
}
