<?php

namespace App\Http\Controllers;

use App\Dtos\Proyecto\CreateActividadDto;
use App\Dtos\Proyecto\UpdateActividadDto;
use App\Http\Requests\Proyecto\ActividadPostRequest;
use App\Http\Requests\Proyecto\ActividadPatchRequest;
use App\Models\Actividad;
use App\Models\Proyecto;
use App\Services\ActividadService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class ActividadController extends BaseController
{

    /**
     * @param ActividadService $actividadService
     */
    public function __construct(
      private readonly ActividadService $actividadService
    )
    {

        //$this->middleware('permission:proyecto.actividad')->only(['lista']);
        //$this->middleware('permission:proyecto.actividad.agregar')->only(['store']);
        //$this->middleware('permission:proyecto.actividad.actualizar')->only(['update']);
    }

    /**
     * @param Proyecto $proyecto
     * @return ResourceCollection
     */
    public function list(Proyecto $proyecto): ResourceCollection
    {
        return $this->actividadService->listByProyectoUuid($proyecto->uuid);
    }

    /**
     * @param Proyecto $proyecto
     * @param ActividadPostRequest $request
     * @return JsonResource
     */
    public function create(Proyecto $proyecto, ActividadPostRequest $request): JsonResource
    {
        $createActividadDto = CreateActividadDto::fromRequest($proyecto->id, $proyecto->uuid, $request);
        return $this->actividadService->createFromDto($createActividadDto);
    }


    /**
     * @param Actividad $actividad
     * @param ActividadPatchRequest $request
     * @return JsonResource
     */
    public function update(Actividad $actividad, ActividadPatchRequest $request):JsonResource
    {
        $updateActividadDto = UpdateActividadDto::fromRequest($request);
        return $this->actividadService->updateFromDto($updateActividadDto, $actividad->id);
    }

    /**
     * @param Actividad $actividad
     * @return Response
     */
    public function delete(Actividad $actividad):Response
    {
        return $this->actividadService->delete($actividad->id);
    }

    /**
     * Obtener el progreso de una actividad
     */
    public function getProgress(Actividad $actividad): JsonResource
    {
        $progress = $this->actividadService->getProgress($actividad->id);
        return JsonResource::make($progress);
    }
}
