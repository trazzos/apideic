<?php

namespace App\Providers;

use App\Events\ActividadCompletedStatusChanged;
use App\Events\TareaCompletedStatusChanged;
use App\Listeners\UpdateActividadStatusOnTareaChange;
use App\Listeners\UpdateProyectoStatusOnActividadChange;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * 
     * Registra los eventos y listeners necesarios para el sistema de actualización
     * automática de estados de completado en la jerarquía Proyecto -> Actividad -> Tarea.
     * 
     * @return void
     */
    public function boot(): void
    {
        // Registrar evento-listener para actualización de actividades cuando cambian las tareas
        Event::listen(
            TareaCompletedStatusChanged::class,
            UpdateActividadStatusOnTareaChange::class
        );

        // Registrar evento-listener para actualización de proyectos cuando cambian las actividades
        Event::listen(
            ActividadCompletedStatusChanged::class,
            UpdateProyectoStatusOnActividadChange::class
        );
    }
}
