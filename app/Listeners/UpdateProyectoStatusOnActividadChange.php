<?php

namespace App\Listeners;

use App\Events\ActividadCompletedStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Listener que actualiza el estado de completado de un proyecto cuando cambia el estado de sus actividades.
 * 
 * Este listener escucha el evento ActividadCompletedStatusChanged y automáticamente
 * actualiza el campo 'completed_at' del proyecto basándose en si todas las
 * actividades asociadas están completadas.
 * 
 * Lógica de negocio:
 * - Un proyecto está completo cuando TODAS sus actividades están completas
 * - Un proyecto está incompleto si al menos una actividad está incompleta
 * - Si no tiene actividades, el proyecto permanece incompleto
 * 
 * @package App\Listeners
 * @author Sistema DEIC
 * @since 1.0.0
 */
class UpdateProyectoStatusOnActividadChange
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
     * Manejar el evento ActividadCompletedStatusChanged.
     * 
     * Cuando una actividad cambia su estado de completado, este método:
     * 1. Verifica que la actividad tenga un proyecto asociado
     * 2. Calcula si todas las actividades del proyecto están completas
     * 3. Actualiza el campo 'completed_at' del proyecto según corresponda
     * 
     * @param ActividadCompletedStatusChanged $event El evento que contiene la actividad modificada
     * @return void
     */
    public function handle(ActividadCompletedStatusChanged $event): void
    {
        $actividad = $event->actividad;
        
        // Verificar que la actividad tenga un proyecto asociado
        if (!$actividad->proyecto) {
            return;
        }

        $proyecto = $actividad->proyecto;

        // Calcular si todas las actividades están completadas
        $totalActividades = $proyecto->actividades()->count();
        $actividadesCompletadas = $proyecto->actividades()->whereNotNull('completed_at')->count();

        // Actualizar el estado del proyecto
        if ($totalActividades > 0 && $actividadesCompletadas === $totalActividades) {
            // Todas las actividades están completas - marcar proyecto como completo
            if (!$proyecto->completed_at) {
                $proyecto->update(['completed_at' => now()]);
            }
        } else {
            // No todas las actividades están completas - marcar proyecto como incompleto
            if ($proyecto->completed_at) {
                $proyecto->update(['completed_at' => null]);
            }
        }
    }
}
