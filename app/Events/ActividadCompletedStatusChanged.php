<?php

namespace App\Events;

use App\Models\Actividad;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento que se dispara cuando el estado de completado de una actividad cambia.
 * 
 * Este evento se utiliza para notificar a los listeners que una actividad ha
 * cambiado su estado de completado (basado en el estado de sus tareas),
 * permitiendo que el sistema actualice automáticamente el estado del proyecto
 * al cual pertenece la actividad.
 * 
 * @package App\Events
 * @author Sistema DEIC
 * @since 1.0.0
 */
class ActividadCompletedStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Crear una nueva instancia del evento.
     * 
     * @param Actividad $actividad La actividad que ha cambiado su estado de completado
     */
    public function __construct(
        public Actividad $actividad
    ) {}
}
