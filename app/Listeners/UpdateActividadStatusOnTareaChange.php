<?php

namespace App\Listeners;

use App\Events\TareaCompletedStatusChanged;
use App\Events\ActividadCompletedStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Listener que actualiza el estado de completado de una actividad cuando cambia el estado de sus tareas.
 * 
 * Este listener escucha el evento TareaCompletedStatusChanged y automáticamente
 * actualiza el campo 'completed_at' de la actividad basándose en si todas las
 * tareas asociadas están completadas. Si el estado de la actividad cambia,
 * dispara un nuevo evento ActividadCompletedStatusChanged.
 * 
 * Lógica de negocio:
 * - Una actividad está completa cuando TODAS sus tareas están completas
 * - Una actividad está incompleta si al menos una tarea está incompleta
 * - Si no tiene tareas, la actividad permanece incompleta
 * 
 * @package App\Listeners
 * @author Sistema DEIC
 * @since 1.0.0
 */
class UpdateActividadStatusOnTareaChange
{
    /**
     * Crear una nueva instancia del listener.
     * 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Manejar el evento TareaCompletedStatusChanged.
     * 
     * Cuando una tarea cambia su estado de completado, este método:
     * 1. Calcula si todas las tareas de la actividad están completas
     * 2. Actualiza el campo 'completed_at' de la actividad según corresponda
     * 3. Si el estado de la actividad cambió, dispara ActividadCompletedStatusChanged
     * 
     * @param TareaCompletedStatusChanged $event El evento que contiene la tarea modificada
     * @return void
     */
    public function handle(TareaCompletedStatusChanged $event): void
    {
        $tarea = $event->tarea;
        $actividad = $tarea->actividad;

        if (!$actividad) {
            return;
        }

        // Calcular si todas las tareas de la actividad están completadas
        $totalTareas = $actividad->tareas()->count();
        $tareasCompletadas = $actividad->tareas()->whereNotNull('completed_at')->count();

        // Guardar el estado anterior para detectar cambios
        $wasCompleted = !is_null($actividad->completed_at);

        // Actualizar el estado de la actividad
        if ($totalTareas > 0 && $tareasCompletadas === $totalTareas) {
            // Todas las tareas están completas - marcar actividad como completa
            if (!$actividad->completed_at) {
                $actividad->update(['completed_at' => now()]);
            }
        } else {
            // No todas las tareas están completas - marcar actividad como incompleta
            if ($actividad->completed_at) {
                $actividad->update(['completed_at' => null]);
            }
        }

        // Si el estado de la actividad cambió, disparar evento para actualizar el proyecto
        $isNowCompleted = !is_null($actividad->fresh()->completed_at);
        if ($wasCompleted !== $isNowCompleted) {
            ActividadCompletedStatusChanged::dispatch($actividad);
        }
    }
}
