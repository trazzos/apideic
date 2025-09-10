<?php

namespace App\Http\Controllers;

use App\Dtos\Dashboard\DashboardDto;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @param DashboardService $dashboardService
     */
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {
    }

    /**
     * Obtener métricas del dashboard de proyectos con filtros jerárquicos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function proyectosDashboard(Request $request): JsonResponse
    {
        try {
            // Crear DTO con filtros jerárquicos basados en el usuario actual
            $dto = DashboardDto::fromUser($request->user());
            
            // Obtener métricas del dashboard aplicando filtros jerárquicos
            $data = $this->dashboardService->getDashboardMetrics($dto);

            return response()->json([
                'success' => true,
                'data' => $data
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
