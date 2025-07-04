<?php

namespace App\Events;

use App\Models\Tarea;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento que se dispara cuando el estado de completado de una tarea cambia.
 * 
 * Este evento se utiliza para notificar a los listeners que una tarea ha sido
 * creada, actualizada o eliminada, permitiendo que otros componentes del sistema
 * reaccionen actualizando el estado de las actividades y proyectos relacionados.
 * 
 * @package App\Events
 * @author Sistema DEIC
 * @since 1.0.0
 */
class TareaCompletedStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Crear una nueva instancia del evento.
     * 
     * @param Tarea $tarea La tarea que ha cambiado su estado de completado
     */
    public function __construct(
        public Tarea $tarea
    ) {}
}
