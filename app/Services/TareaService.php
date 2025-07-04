<?php

namespace App\Services;

use App\Events\TareaCompletedStatusChanged;
use App\Repositories\Eloquent\TareaRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

/**
 * Servicio para la gestión de tareas con eventos automáticos.
 * 
 * Este servicio maneja las operaciones CRUD de tareas y dispara eventos
 * automáticamente para mantener actualizados los estados de actividades
 * y proyectos relacionados. No extiende BaseService debido a que requiere
 * lógica específica de eventos en todos los métodos de modificación.
 * 
 * @package App\Services
 * @author Sistema DEIC
 * @since 1.0.0
 */
class TareaService 
{
    /**
     * Repositorio para operaciones de base de datos de tareas.
     */
    private readonly TareaRepository $tareaRepository;
    
    /**
     * Clase del recurso de colección personalizada.
     */
    private string $customResourceCollection = "App\\Http\\Resources\\Tarea\\TareaCollection";
    
    /**
     * Clase del recurso individual personalizada.
     */
    private string $customResource = "App\\Http\\Resources\\Tarea\\TareaResource";

    /**
     * Constructor del servicio de tareas.
     * 
     * @param TareaRepository $tareaRepository Repositorio para operaciones de BD
     */
    public function __construct(TareaRepository $tareaRepository)
    {
        $this->tareaRepository = $tareaRepository;
    }

    /**
     * Listar tareas con paginación.
     * 
     * @param int $perPage Número de elementos por página
     * @param array $columns Columnas a seleccionar
     * @param string $pageName Nombre del parámetro de página
     * @param int|null $page Página específica
     * @return ResourceCollection Colección paginada de tareas
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null): ResourceCollection
    {
        $rows = $this->tareaRepository->paginate($perPage, $columns, $pageName, $page);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Listar todas las tareas.
     * 
     * @return ResourceCollection Colección de todas las tareas
     */
    public function list(): ResourceCollection
    {
        $rows = $this->tareaRepository->all();

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Buscar una tarea por ID.
     * 
     * @param int $id El ID de la tarea
     * @return JsonResource La tarea encontrada como recurso JSON
     */
    public function findById($id): JsonResource
    {
        $row = $this->tareaRepository->findById($id);

        if ($this->customResource) {
            return new $this->customResource($row);
        }

        return JsonResource::make($row);
    }

    /**
     * Listar tareas por UUID de actividad.
     * 
     * @param string $uuid UUID de la actividad
     * @return ResourceCollection Colección de tareas de la actividad
     */
    public function listByActividadUuid(string $uuid): ResourceCollection
    {
        $rows = $this->tareaRepository->findByActividadUuid($uuid);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }
        
        return ResourceCollection::make($rows);
    }

    /**
     * Crear una nueva tarea.
     * 
     * Después de crear la tarea, dispara el evento TareaCompletedStatusChanged
     * para que el sistema actualice automáticamente los estados de la actividad
     * y proyecto asociados según sea necesario.
     * 
     * @param array $data Los datos para crear la tarea
     * @return JsonResource La tarea creada como recurso JSON
     * @throws \Exception Si hay un error al crear la tarea
     */
    public function create(array $data): JsonResource
    {
        $nuevaTarea = $this->tareaRepository->create($data);
        
        // Disparar evento para actualizar estados automáticamente
        TareaCompletedStatusChanged::dispatch($nuevaTarea);
        
        if ($this->customResource) {
            return new $this->customResource($nuevaTarea);
        }
        
        return JsonResource::make($nuevaTarea);
    }

    /**
     * Actualizar una tarea existente.
     * 
     * Después de actualizar la tarea, dispara el evento TareaCompletedStatusChanged
     * para que el sistema actualice automáticamente los estados de la actividad
     * y proyecto asociados según sea necesario.
     * 
     * @param int $id El ID de la tarea a actualizar
     * @param array $data Los datos para actualizar la tarea
     * @return JsonResource La tarea actualizada como recurso JSON
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la tarea no existe
     */
    public function update(int $id, array $data): JsonResource
    {
        $tareaActualizada = $this->tareaRepository->updateAndReturn($id, $data);
        
        if (!$tareaActualizada) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Registro con ID {$id} no encontrado para actualizar.");
        }
        
        // Disparar evento para actualizar estados automáticamente
        TareaCompletedStatusChanged::dispatch($tareaActualizada);
        
        if ($this->customResource) {
            return new $this->customResource($tareaActualizada);
        }
        
        return JsonResource::make($tareaActualizada);
    }

    /**
     * Eliminar una tarea.
     * 
     * Después de eliminar la tarea, dispara el evento TareaCompletedStatusChanged
     * para que el sistema actualice automáticamente los estados de la actividad
     * y proyecto asociados según sea necesario.
     * 
     * @param int $id El ID de la tarea a eliminar
     * @return Response Respuesta HTTP sin contenido (204)
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la tarea no existe
     */
    public function delete($id): Response
    {
        $tarea = $this->tareaRepository->findById($id);
        
        if (!$tarea) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("No se encontró registro con ID {$id} para eliminar.");
        }
        
        // Guardar referencia a la actividad antes de eliminar
        $actividad = $tarea->actividad;
        $deleted = $this->tareaRepository->delete($id);
        
        if ($deleted) {
            // Disparar evento para actualizar estados después de eliminar
            // Creamos una tarea temporal con la actividad para el evento
            $tareaTemp = new \App\Models\Tarea();
            $tareaTemp->setRelation('actividad', $actividad);
            TareaCompletedStatusChanged::dispatch($tareaTemp);
            
            return response()->noContent();
        } else {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("No se encontró registro con ID {$id} para eliminar.");
        }
    }

    /**
     * Marcar una tarea como completada.
     * 
     * Establece el campo 'completed_at' con la fecha y hora actual,
     * y dispara automáticamente los eventos necesarios para actualizar
     * los estados de la actividad y proyecto relacionados.
     * 
     * @param int $id El ID de la tarea a completar
     * @return JsonResource La tarea completada como recurso JSON
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la tarea no existe
     */
    public function complete(int $id): JsonResource
    {
        $data = ['completed_at' => now()];
        return $this->update($id, $data);
    }

    /**
     * Marcar una tarea como pendiente (no completada).
     * 
     * Establece el campo 'completed_at' como null, indicando que la tarea
     * no está completada, y dispara automáticamente los eventos necesarios
     * para actualizar los estados de la actividad y proyecto relacionados.
     * 
     * @param int $id El ID de la tarea a marcar como pendiente
     * @return JsonResource La tarea actualizada como recurso JSON
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la tarea no existe
     */
    public function markAsPending(int $id): JsonResource
    {
        $data = ['completed_at' => null];
        return $this->update($id, $data);
    }

}
