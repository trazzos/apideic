<?php

namespace App\Http\Controllers;

use App\Http\Requests\Persona\CreatePersonaRequest;
use App\Http\Requests\Persona\UpdatePersonaRequest;
use App\Services\PersonaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Controlador para gestión de personas con funcionalidades de búsqueda avanzada.
 * 
 * Ejemplo de implementación del sistema de búsqueda optimizado.
 */
class PersonaSearchController extends Controller
{
    public function __construct(
        private readonly PersonaService $personaService
    ) {}

    /**
     * Buscar personas con criterios avanzados.
     * 
     * Parámetros de búsqueda soportados:
     * - search: Término de búsqueda en nombre, apellido, email, teléfono
     * - departamento_id: Filtrar por departamento
     * - active: Filtrar por estado activo/inactivo
     * - created_at_from/created_at_to: Rango de fechas de creación
     * - sort_by: Campo para ordenar (nombre, apellido, email, created_at)
     * - sort_direction: Dirección del ordenamiento (asc, desc)
     * - per_page: Cantidad de registros por página
     * - with: Relaciones a cargar (ej: "departamento,user")
     * 
     * @param Request $request
     * @return ResourceCollection|JsonResponse
     */
    public function search(Request $request)
    {
        try {
            return $this->personaService->searchPersonas($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al buscar personas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Búsqueda rápida de personas.
     * 
     * Busca en campos principales: nombre, apellido, email
     * 
     * @param Request $request
     * @return ResourceCollection|JsonResponse
     */
    public function quickSearch(Request $request)
    {
        $request->validate([
            'term' => 'required|string|min:2|max:100'
        ]);

        try {
            return $this->personaService->quickSearchPersonas($request->get('term'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en búsqueda rápida',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ejemplo de búsqueda con filtros específicos.
     * 
     * @param Request $request
     * @return ResourceCollection|JsonResponse
     */
    public function searchByDepartment(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|integer|exists:departamentos,id',
            'active' => 'sometimes|boolean'
        ]);

        try {
            // Crear criterios personalizados
            $criteria = \App\Services\Search\SearchCriteria::fromRequest($request)
                ->setSearchFields(['nombre', 'apellido', 'email'])
                ->setSort('nombre', 'asc')
                ->setRelations(['departamento']);

            return $this->personaService->searchWithPagination($criteria, $request->get('per_page', 15));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al buscar por departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
