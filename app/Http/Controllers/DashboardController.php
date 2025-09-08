<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Obtener métricas del dashboard de proyectos
     *
     * @return JsonResponse
     */
    public function proyectosDashboard(): JsonResponse
    {
        try {
            // 1. Número total de proyectos registrados e indicar cuántos departamentos participan
            $totalProyectos = Proyecto::count();
            $departamentosParticipantes = Proyecto::distinct('departamento_id')->count('departamento_id');

            // 2. Porcentaje de avance global de todos los proyectos
            $proyectos = Proyecto::with('actividades')->get();
            $proyectosCompletados = $proyectos->filter(function ($proyecto) {
                return $proyecto->isCompleted();
            })->count();
            
            $porcentajeAvanceGlobal = $totalProyectos > 0 
                ? round(($proyectosCompletados / $totalProyectos) * 100, 2) 
                : 0;

            // 3. Número de proyectos por departamento (solo departamentos con proyectos)
            $proyectosPorDepartamento = Proyecto::select('departamento_id', DB::raw('count(*) as total_proyectos'))
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

            // 4. Porcentaje de avance de actividades de los 10 últimos proyectos
            $ultimosProyectos = Proyecto::with(['actividades', 'departamento'])
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

            // 5. Los primeros 10 proyectos prioritarios mostrando su porcentaje de avance
            // Como no hay campo prioridad, usaremos los proyectos con más actividades como "prioritarios"
            $proyectosPrioritarios = Proyecto::with(['actividades', 'departamento'])
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

            // 6. Las últimas 10 actividades completadas
            $ultimasActividadesCompletadas = Actividad::with(['proyecto:uuid,nombre', 'responsable:id,nombre,apellido_paterno'])
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($actividad) {
                    return [
                        'uuid' => $actividad->uuid,
                        'nombre' => $actividad->nombre,
                        'proyecto_uuid' => $actividad->proyecto->uuid ?? null,
                        'proyecto_nombre' => $actividad->proyecto->nombre ?? null,
                        'responsable_nombre' => $actividad->responsable 
                            ? $actividad->responsable->nombre . ' ' . $actividad->responsable->apellido_paterno
                            : null,
                        'completed_at' => $actividad->completed_at,
                        'fecha_completada' => $actividad->completed_at?->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'resumen_general' => [
                        'total_proyectos' => $totalProyectos,
                        'departamentos_participantes' => $departamentosParticipantes,
                        'porcentaje_avance_global' => $porcentajeAvanceGlobal,
                        'proyectos_completados' => $proyectosCompletados
                    ],
                    'proyectos_por_departamento' => $proyectosPorDepartamento,
                    'ultimos_proyectos' => $ultimosProyectos,
                    'proyectos_prioritarios' => $proyectosPrioritarios,
                    'ultimas_actividades_completadas' => $ultimasActividadesCompletadas
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener métricas del dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
