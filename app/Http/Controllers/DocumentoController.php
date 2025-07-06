<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shared\DocumentoRequest;
use App\Models\Actividad;
use App\Models\Proyecto;
use App\Services\DocumentoService;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión de documentos de actividades.
 * 
 * @package App\Http\Controllers
 * @author Sistema DEIC
 * @since 1.0.0
 */
class DocumentoController extends Controller
{
    /**
     * Servicio para la gestión de documentos.
     */
    private readonly DocumentoService $documentoService;

    /**
     * Constructor del controlador de documentos.
     * 
     * @param DocumentoService $documentoService
     */
    public function __construct(DocumentoService $documentoService)
    {
        $this->documentoService = $documentoService;
    }

    /**
     * Listar documentos de una actividad.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad de la cual listar documentos
     * @return ResourceCollection
     */
    public function list(Proyecto $proyecto, Actividad $actividad): ResourceCollection
    {
        try {
            return $this->documentoService->list($proyecto, $actividad);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Subir un documento para una actividad específica.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que se asociará el documento
     * @param DocumentoRequest $request Datos del documento validados
     * @return JsonResponse
     */
    public function create(Proyecto $proyecto, Actividad $actividad, DocumentoRequest $request): JsonResponse
    {
        try {
            return $this->documentoService->create($proyecto, $actividad, $request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Eliminar un documento.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que pertenece el documento
     * @param Archivo $archivo Archivo a eliminar
     * @return JsonResponse
     */
    public function delete(Proyecto $proyecto, Actividad $actividad, Archivo $archivo): JsonResponse
    {
        try {
          return $this->documentoService->delete($proyecto, $actividad, $archivo);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
