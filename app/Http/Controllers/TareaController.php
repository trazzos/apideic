<?php

namespace App\Http\Controllers;

use App\Http\Requests\Proyecto\TareaPostRequest;
use App\Http\Requests\Proyecto\TareaPatchRequest;
use App\Models\Actividad;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Services\TareaService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class TareaController extends BaseController
{

    /**
     * @param TareaService $tareaService
     */
    public function __construct(
      private readonly TareaService $tareaService
    )
    {

        //$this->middleware('permission:proyecto.actividad')->only(['lista']);
        //$this->middleware('permission:proyecto.actividad.agregar')->only(['store']);
        //$this->middleware('permission:proyecto.actividad.actualizar')->only(['update']);
    }

    /**
     * @param Proyecto $proyecto
     * @param Actividad $actividad
     * @return ResourceCollection
     */
    public function list(Proyecto $proyecto, Actividad $actividad): ResourceCollection
    {
        return $this->tareaService->listByActividadUuid($actividad->uuid);
    }

    /**
     * @param Proyecto $proyecto
     * @param Actividad $actividad
     * @param TareaPostRequest $request
     * @return JsonResource
     */
    public function create(Proyecto $proyecto, Actividad $actividad, TareaPostRequest $request): JsonResource
    {


        $data = $request->validated();
        $data['actividad_id']   = $actividad->id;
        $data['actividad_uuid'] = $actividad->uuid;
        return $this->tareaService->create($data);
    }


    /**
     * @param Proyecto $proyecto
     * @param Actividad $actividad
     * @param Tarea $tarea
     * @param TareaPatchRequest $request
     * @return JsonResource
     */
    public function update(Proyecto $proyecto, Actividad $actividad, Tarea $tarea, TareaPatchRequest $request):JsonResource
    {
        $data = $request->validated();
        return $this->tareaService->update($tarea->id, $data);
    }

    /**
     * @param Proyecto $proyecto
     * @param Actividad $actividad
     * @param Tarea $tarea
     * @return Response
     */
    public function delete(Proyecto $proyecto, Actividad $actividad, Tarea $tarea,):Response
    {
        return $this->tareaService->delete($tarea->id);
    }

    /**
     * @param Proyecto $proyecto
     * @param Actividad $actividad
     * @param Tarea $tarea
     * @return Response
     */
    public function changeStatus(Proyecto $proyecto, Actividad $actividad, Tarea $tarea,):JsonResource
    {
        $data['completed_at'] = $tarea->completed_at ? null : now();
        return $this->tareaService->update($tarea->id, $data);
    }
}
