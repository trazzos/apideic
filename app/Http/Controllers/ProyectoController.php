<?php

namespace App\Http\Controllers;

use App\Dtos\Proyecto\CreateProyectoDto;
use App\Dtos\Proyecto\UpdateProyectoDto;
use App\Http\Requests\Proyecto\ProyectoPostRequest;
use App\Http\Requests\Proyecto\ProyectoPutRequest;
use App\Models\Proyecto;
use App\Services\ProyectoService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class ProyectoController extends BaseController
{

    /**
     * @param ProyectoService $proyectoService
     */
    public function __construct(
      private readonly ProyectoService $proyectoService
    )
    {

        //$this->middleware('permission:proyecto')->only(['lista']);
        //$this->middleware('permission:proyecto.crear')->only(['store']);
        //$this->middleware('permission:proyecto.editar')->only(['update']);
    }

    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return $this->proyectoService->lista();
    }

    /**
     * @param int $id
     * @return JsonResource
     */
    public function show(int $id):JsonResource
    {
        return $this->proyectoService->findById($id);
    }

    /**
     * @param ProyectoPostRequest $request
     * @return JsonResource
     */
    public function create(ProyectoPostRequest $request): JsonResource
    {
        $createProyectoDto = CreateProyectoDto::fromRequest($request);
        return $this->proyectoService->crearDesdeDto($createProyectoDto);
    }


    /**
     * @param Proyecto $proyectoS
     * @param ProyectoPutRequest $request
     * @return JsonResource
     */
    public function update(Proyecto $proyectos, ProyectoPutRequest $request):JsonResource
    {
        $updateProyectoDto = UpdateProyectoDto::fromRequest($request);
        return $this->proyectoService->actualizarDesdeDto($updateProyectoDto, $proyectos->id,);
    }

    /**
     * @param Proyecto $proyectos
     * @return Response
     */
    public function delete(Proyecto $proyectos):Response
    {
        return $this->proyectoService->eliminar($proyectos->id);
    }
}
