<?php

namespace App\Services;

use App\Dtos\Dashboard\DashboardDto;
use App\Models\Actividad;
use App\Models\Departamento;
use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Obtener métricas del dashboard con filtros jerárquicos
     * 
     * @param DashboardDto $dto DTO con información del usuario y filtros jerárquicos
     * @return array Array con todas las métricas del dashboard organizadas por sección
     */
    public function getDashboardMetrics(DashboardDto $dto): array
    {
        // Obtener query base para proyectos con filtros jerárquicos
        $proyectosQuery = $this->getProjectsQuery($dto);
        $actividadesQuery = $this->getActivitiesQuery($dto);

        return [
            'resumen_general' => $this->getResumenGeneral($proyectosQuery, $actividadesQuery),
            'proyectos_por_departamento' => $this->getProyectosPorDepartamento($proyectosQuery),
            'ultimos_proyectos' => $this->getUltimosProyectos($proyectosQuery),
            'proyectos_prioritarios' => $this->getProyectosPrioritarios($proyectosQuery),
            'ultimas_actividades_completadas' => $this->getUltimasActividadesCompletadas($actividadesQuery)
        ];
    }

    /**
     * Obtener query base para proyectos con filtros jerárquicos
     * 
     * Aplica filtros según el nivel jerárquico del usuario:
     * - Titulares/Superiores: proyectos de departamentos accesibles
     * - No titulares: solo proyectos donde es responsable de al menos una actividad
     * 
     * @param DashboardDto $dto DTO con información del usuario y permisos
     * @return Builder Query builder configurado con filtros jerárquicos para proyectos
     */
    private function getProjectsQuery(DashboardDto $dto): Builder
    {
        $query = Proyecto::query();

        if ($dto->hasProjectRestrictions()) {
            // Verificar si la persona puede ver proyectos del departamento completo
            if ($dto->canViewDepartmentActivities()) {
                // Usuario titular de departamento o niveles superiores: ve proyectos por departamentos
                $accessibleDepartmentIds = $dto->getAccessibleDepartmentIds();
                if (!empty($accessibleDepartmentIds)) {
                    $query->whereIn('departamento_id', $accessibleDepartmentIds);
                } else {
                    $query->whereRaw('1 = 0'); // Sin acceso
                }
            } else {
                // Usuario no titular de departamento: solo proyectos donde es responsable de al menos una actividad
                $persona = $dto->getPersona();
                if ($persona) {
                    $query->whereHas('actividades', function ($q) use ($persona) {
                        $q->where('responsable_id', $persona->id);
                    });
                } else {
                    $query->whereRaw('1 = 0'); // Sin acceso
                }
            }
        }

        return $query;
    }

    /**
     * Obtener query base para actividades con filtros jerárquicos
     * 
     * Aplica filtros según el nivel jerárquico del usuario:
     * - Titulares/Superiores: actividades de proyectos de departamentos accesibles
     * - No titulares: solo actividades donde es responsable directo
     * 
     * @param DashboardDto $dto DTO con información del usuario y permisos
     * @return Builder Query builder configurado con filtros jerárquicos para actividades
     */
    private function getActivitiesQuery(DashboardDto $dto): Builder
    {
        $query = Actividad::query();

        if ($dto->hasActivityRestrictions()) {
            $persona = $dto->getPersona();
            
            // Verificar si la persona puede ver actividades del departamento completo
            if ($dto->canViewDepartmentActivities()) {
                // Usuario titular de departamento o niveles superiores: ve actividades por departamentos
                $accessibleDepartmentIds = $dto->getAccessibleDepartmentIds();
                if (!empty($accessibleDepartmentIds)) {
                    $query->whereHas('proyecto', function ($q) use ($accessibleDepartmentIds) {
                        $q->whereIn('departamento_id', $accessibleDepartmentIds);
                    });
                } else {
                    $query->whereRaw('1 = 0'); // Sin acceso
                }
            } else {
                // Usuario no titular de departamento: solo sus actividades donde es responsable
                if ($persona) {
                    $query->where('responsable_id', $persona->id);
                } else {
                    $query->whereRaw('1 = 0'); // Sin acceso
                }
            }
        }

        return $query;
    }

    /**
     * Obtener resumen general de métricas del dashboard
     * 
     * Calcula estadísticas generales como total de proyectos, departamentos participantes,
     * porcentaje de avance global y proyectos completados
     * 
     * @param Builder $proyectosQuery Query builder para proyectos con filtros aplicados
     * @param Builder $actividadesQuery Query builder para actividades con filtros aplicados
     * @return array Array con métricas del resumen general
     */
    private function getResumenGeneral(Builder $proyectosQuery, Builder $actividadesQuery): array
    {
        // Clonar queries para no afectar las posteriores consultas
        $proyectos = clone $proyectosQuery;
        $actividades = clone $actividadesQuery;

        $totalProyectos = $proyectos->count();
        
        $departamentosParticipantes = $proyectos->distinct('departamento_id')
            ->count('departamento_id');

        // Calcular porcentaje de avance global
        $proyectosConActividades = $proyectos->with('actividades')->get();
        $proyectosCompletados = $proyectosConActividades->filter(function ($proyecto) {
            return $proyecto->isCompleted();
        })->count();

        $porcentajeAvanceGlobal = $totalProyectos > 0 
            ? round(($proyectosCompletados / $totalProyectos) * 100, 2) 
            : 0;

        return [
            'total_proyectos' => $totalProyectos,
            'departamentos_participantes' => $departamentosParticipantes,
            'porcentaje_avance_global' => $porcentajeAvanceGlobal,
            'proyectos_completados' => $proyectosCompletados
        ];
    }

    /**
     * Obtener proyectos agrupados por departamento
     * 
     * Cuenta el número de proyectos por cada departamento participante,
     * incluye el nombre del departamento en los resultados
     * 
     * @param Builder $proyectosQuery Query builder para proyectos con filtros aplicados
     * @return \Illuminate\Support\Collection Colección con proyectos agrupados por departamento
     */
    private function getProyectosPorDepartamento(Builder $proyectosQuery): \Illuminate\Support\Collection
    {
        // Clonar query para evitar conflictos con otros métodos
        $query = clone $proyectosQuery;
        
        return $query->select('departamento_id', DB::raw('count(*) as total_proyectos'))
            ->with('departamento:id,nombre')
            ->groupBy('departamento_id')
            ->get()
            ->map(function ($item) {
                return [
                    'departamento_id' => $item->departamento_id,
                    'departamento_nombre' => $item->departamento->nombre ?? 'Sin departamento',
                    'total_proyectos' => $item->total_proyectos
                ];
            });
    }

    /**
     * Obtener los últimos proyectos creados con su progreso
     * 
     * Retorna los 10 proyectos más recientes ordenados por fecha de creación,
     * incluyendo información de progreso y departamento
     * 
     * @param Builder $proyectosQuery Query builder para proyectos con filtros aplicados
     * @return \Illuminate\Support\Collection Colección con los últimos 10 proyectos y su progreso
     */
    private function getUltimosProyectos(Builder $proyectosQuery): \Illuminate\Support\Collection
    {
        // Clonar query para evitar conflictos con otros métodos
        $query = clone $proyectosQuery;
        
        return $query->with(['actividades', 'departamento'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($proyecto) {
                $progreso = $proyecto->getProgress();
                return [
                    'uuid' => $proyecto->uuid,
                    'nombre' => $proyecto->nombre,
                    'porcentaje_avance' => $progreso['porcentaje_completado'],
                    'actividades_completadas' => $progreso['actividades_completadas'],
                    'total_actividades' => $progreso['total_actividades'],
                    'departamento_nombre' => $proyecto->departamento->nombre ?? null,
                    'created_at' => $proyecto->created_at
                ];
            });
    }

    /**
     * Obtener proyectos prioritarios basados en número de actividades
     * 
     * Retorna los 10 proyectos con mayor número de actividades como "prioritarios",
     * ordenados descendentemente por cantidad de actividades
     * 
     * @param Builder $proyectosQuery Query builder para proyectos con filtros aplicados
     * @return \Illuminate\Support\Collection Colección con los 10 proyectos prioritarios y su progreso
     */
    private function getProyectosPrioritarios(Builder $proyectosQuery): \Illuminate\Support\Collection
    {
        // Clonar query para evitar conflictos con otros métodos
        $query = clone $proyectosQuery;
        
        return $query->with(['actividades', 'departamento'])
            ->withCount('actividades')
            ->orderBy('actividades_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($proyecto) {
                $progreso = $proyecto->getProgress();
                return [
                    'uuid' => $proyecto->uuid,
                    'nombre' => $proyecto->nombre,
                    'total_actividades' => $proyecto->actividades_count,
                    'porcentaje_avance' => $progreso['porcentaje_completado'],
                    'actividades_completadas' => $progreso['actividades_completadas'],
                    'departamento_nombre' => $proyecto->departamento->nombre ?? null
                ];
            });
    }

    /**
     * Obtener las últimas actividades completadas
     * 
     * Retorna las 10 actividades completadas más recientes ordenadas por fecha de completado,
     * incluyendo información del proyecto asociado y responsable
     * 
     * @param Builder $actividadesQuery Query builder para actividades con filtros aplicados
     * @return \Illuminate\Support\Collection Colección con las últimas 10 actividades completadas
     */
    private function getUltimasActividadesCompletadas(Builder $actividadesQuery): \Illuminate\Support\Collection
    {
        // Clonar query para evitar conflictos con otros métodos
        $query = clone $actividadesQuery;
        
        return $query->with(['proyecto:id,uuid,nombre', 'responsable:id,nombre,apellido_paterno'])
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($actividad) {
                return [
                    'uuid' => $actividad->uuid,
                    'nombre' => $actividad->nombre,
                    'proyecto_uuid' => $actividad?->proyecto?->uuid ?? null,
                    'proyecto_nombre' => $actividad?->proyecto?->nombre ?? null,
                    'responsable_nombre' => $actividad->responsable 
                        ? $actividad->responsable->nombre . ' ' . $actividad->responsable->apellido_paterno
                        : null,
                    'completed_at' => $actividad->completed_at,
                    'fecha_completada' => $actividad->completed_at?->format('Y-m-d H:i:s')
                ];
            });
    }
}
