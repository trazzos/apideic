<?php

namespace App\Http\Controllers;

use App\Http\Resources\Catalogo\CatalogoCollection;
use App\Services\CatalogoService;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión consolidada de catálogos.
 * 
 * Proporciona endpoints para obtener todos los catálogos del sistema
 * en una sola petición, optimizando el rendimiento y la experiencia de usuario.
 */
class CatalogoController extends Controller
{
    /**
     * Servicio para la gestión de catálogos.
     */
    private readonly CatalogoService $catalogoService;

    /**
     * Constructor del controlador.
     *
     * @param CatalogoService $catalogoService Servicio para operaciones de catálogos
     */
    public function __construct(CatalogoService $catalogoService)
    {
        $this->catalogoService = $catalogoService;
    }

    /**
     * Obtener todos los catálogos del sistema.
     *
     * Devuelve una colección consolidada con todos los catálogos:
     * Departamentos, Tipos de Documento, Tipos de Proyecto, Tipos de Actividad,
     * Beneficiarios, Autoridades, Responsables y Capacitadores.
     *
     * @return CatalogoCollection|JsonResponse Colección de catálogos o error
     */
    public function all()
    {
        try {
            $catalogos = $this->catalogoService->getAllCatalogos();
            return new CatalogoCollection($catalogos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los catálogos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
