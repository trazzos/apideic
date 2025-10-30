<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

use App\Http\Requests\Organizacion\UnidadApoyoPostRequest;
use App\Http\Requests\Organizacion\UnidadApoyoPatchRequest;

use App\Services\UnidadApoyoService;
use App\Models\UnidadApoyo;

class UnidadApoyoController extends BaseController
{
    public function __construct(
        private readonly UnidadApoyoService $unidadApoyoService
    )
    {
        //$this->middleware('permission:unidadapoyo')->only(['list']);
        //$this->middleware('permission:unidadapoyo.crear')->only(['create']);
        //$this->middleware('permission:unidadapoyo.editar')->only(['update']);
        //$this->middleware('permission:unidadapoyo.eliminar')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return $this->unidadApoyoService->list();
    }

    /**
     * @param UnidadApoyoPostRequest $request
     * @return JsonResource
     */
    public function create(UnidadApoyoPostRequest $request): JsonResource
    {
        $data = $request->validated();
        return $this->unidadApoyoService->create($data);
    }

    /**
     * Update the specified resource in storage.
     * @param UnidadApoyo $unidadApoyo
     * @return JsonResource
     */
    public function update(UnidadApoyo $unidadApoyo, UnidadApoyoPatchRequest $request): JsonResource
    {
        return $this->unidadApoyoService->update($unidadApoyo->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     * @param UnidadApoyo $unidadApoyo
     * @return Response
     */
    public function delete(UnidadApoyo $unidadApoyo): Response
    {
        return $this->unidadApoyoService->delete($unidadApoyo->id);
    }
}
