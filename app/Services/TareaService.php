<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Repositories\Eloquent\TareaRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TareaService extends BaseService {

    /**
     * @param TareaRepository $tareaRepository
     */
    public function __construct(private readonly TareaRepository $tareaRepository)
    {
        $this->repository = $this->tareaRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Tarea\\TareaCollection";
        $this->customResource = "App\\Http\\Resources\\Tarea\\TareaResource";
    }

    public function listByActividadUuid(string $uuid):ResourceCollection
    {
        $rows = $this->repository->findByActividadUuid($uuid);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }
        return ($this->customResourceCollection)::make($rows);
    }

    /**
     * Crear una tarea y actualizar el proyecto si es necesario
     */
    public function create(array $data): JsonResource
    {
        $nuevaTarea = $this->repository->create($data);
        
        // Actualizar el estado del proyecto y actividad
        $this->updateCompletedStatus($nuevaTarea->actividad_id);
        
        if ($this->customResource) {
            return new $this->customResource($nuevaTarea);
        }
        
        return JsonResource::make($nuevaTarea);
    }

    /**
     * Actualizar una tarea y el estado del proyecto
     */
    public function update(int $id, array $data): JsonResource
    {
        $tareaActualizada = $this->repository->updateAndReturn($id, $data);
        
        if (!$tareaActualizada) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Registro con ID {$id} no encontrado para actualizar.");
        }
        
        // Actualizar el estado del proyecto y actividad
        $this->updateCompletedStatus($tareaActualizada->actividad_id);
        
        if ($this->customResource) {
            return new $this->customResource($tareaActualizada);
        }
        
        return JsonResource::make($tareaActualizada);
    }

    /**
     * Eliminar una tarea y actualizar el estado del proyecto
     */
    public function delete($id): Response
    {
        $tarea = $this->repository->find($id);
        
        if (!$tarea) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("No se encontró registro con ID {$id} para eliminar.");
        }
        
        $actividadId = $tarea->actividad_id;
        $deleted = $this->repository->delete($id);
        
        if ($deleted) {
            // Actualizar el estado del proyecto y actividad después de eliminar la tarea
            $this->updateCompletedStatus($actividadId);
            return response()->noContent();
        } else {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("No se encontró registro con ID {$id} para eliminar.");
        }
    }

    /**
     * Marcar una tarea como completada
     */
    public function complete(int $id): JsonResource
    {
        $data = ['completed_at' => now()];
        return $this->update($id, $data);
    }

    /**
     * Marcar una tarea como pendiente (no completada)
     */
    public function markAsPending(int $id): JsonResource
    {
        $data = ['completed_at' => null];
        return $this->update($id, $data);
    }

    /**
     * Actualizar el campo completed_at de la actividad y proyecto basado en el estado de las tareas
     */
    private function updateCompletedStatus(int $actividadId): void
    {
        // Obtener la actividad con su proyecto y todas las tareas
        $actividad = \App\Models\Actividad::with(['proyecto', 'tareas'])->find($actividadId);
        
        if (!$actividad) {
            return;
        }

        // Actualizar el estado de la actividad
        $this->updateActividadCompletedStatus($actividad);

        // Actualizar el estado del proyecto si existe
        if ($actividad->proyecto) {
            $this->updateProyectoCompletedStatus($actividad->proyecto);
        }
    }

    /**
     * Actualizar el campo completed_at de una actividad específica
     */
    private function updateActividadCompletedStatus(\App\Models\Actividad $actividad): void
    {
        $totalTareas = $actividad->tareas()->count();
        $tareasCompletadas = $actividad->tareas()->whereNotNull('completed_at')->count();

        // Si hay tareas y todas están completas, marcar la actividad como completa
        if ($totalTareas > 0 && $tareasCompletadas === $totalTareas) {
            if (!$actividad->completed_at) {
                $actividad->update(['completed_at' => now()]);
            }
        } else {
            // Si no todas las tareas están completas, quitar la fecha de completado
            if ($actividad->completed_at) {
                $actividad->update(['completed_at' => null]);
            }
        }
    }

    /**
     * Actualizar el campo completed_at de un proyecto basado en el estado de todas sus actividades
     */
    private function updateProyectoCompletedStatus(\App\Models\Proyecto $proyecto): void
    {
        // Obtener todas las actividades del proyecto con sus tareas
        $todasLasActividades = $proyecto->actividades()->with('tareas')->get();
        
        $todasLasTareasCompletas = true;
        $hayTareas = false;
        
        foreach ($todasLasActividades as $act) {
            foreach ($act->tareas as $tarea) {
                $hayTareas = true;
                if (!$tarea->completed_at) {
                    $todasLasTareasCompletas = false;
                    break 2; // Salir de ambos loops
                }
            }
        }
        
        // Si hay tareas y todas están completas, marcar el proyecto como completo
        if ($hayTareas && $todasLasTareasCompletas) {
            if (!$proyecto->completed_at) {
                $proyecto->update(['completed_at' => now()]);
            }
        } else {
            // Si no todas las tareas están completas, quitar la fecha de completado
            if ($proyecto->completed_at) {
                $proyecto->update(['completed_at' => null]);
            }
        }
    }

}
