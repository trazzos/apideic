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
use Illuminate\Pagination\LengthAwarePaginator;
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

        return $this->proyectoService->list();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function paginate(Request $request): mixed
    {

        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);
        return $this->proyectoService->paginate($perPage, ['*'], 'page', $page);
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
