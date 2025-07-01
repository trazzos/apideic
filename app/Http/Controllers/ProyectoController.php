<?php

namespace App\Http\Controllers;

use App\Dtos\Proyecto\CreateProyectoDto;
use App\Dtos\Proyecto\UpdateProyectoDto;
use App\Http\Requests\Proyecto\ProyectoPostRequest;
use App\Http\Requests\Proyecto\ProyectoPatchRequest;
use App\Models\Proyecto;
use App\Services\ProyectoService;
use Illuminate\Http\Request;
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

    public function show(Proyecto $proyecto): JsonResource
    {
        return $this->proyectoService->findById($proyecto->id);
    }

    //implementar paginacion para list


    /**
     * @return ResourceCollection
     */
    public function list(Request $request): ResourceCollection
    {
        if ($request->has('per_page')) {
            $perPage = (int)$request->get('per_page');
            $page = (int)$request->get('page');
            return $this->proyectoService->paginate($perPage,['*'], 'page', $page);
        }
        return $this->proyectoService->list();
    }

    /**
     * @param ProyectoPostRequest $request
     * @return JsonResource
     */
    public function create(ProyectoPostRequest $request): JsonResource
    {
        $createProyectoDto = CreateProyectoDto::fromRequest($request);
        return $this->proyectoService->createFromDto($createProyectoDto);
    }


    /**
     * @param Proyecto $proyecto
     * @param ProyectoPatchRequest $request
     * @return JsonResource
     */
    public function update(Proyecto $proyecto, ProyectoPatchRequest $request):JsonResource
    {
        $updateProyectoDto = UpdateProyectoDto::fromRequest($request);
        return $this->proyectoService->updateFromDto($updateProyectoDto, $proyecto->id,);
    }

    /**
     * @param Proyecto $proyecto
     * @return Response
     */
    public function delete(Proyecto $proyecto):Response
    {
        return $this->proyectoService->delete($proyecto->id);
    }
}
