<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shared\ArchivoRequest;
use App\Models\Actividad;
use App\Models\Proyecto;
use App\Services\ArchivoService;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión de archivos de actividades.
 * 
 * @package App\Http\Controllers
 * @author Sistema DEIC
 * @since 1.0.0
 */
class ArchivoController extends Controller
{
    /**
     * Servicio para la gestión de archivos.
     */
    private readonly ArchivoService $archivoService;

    /**
     * Constructor del controlador de archivos.
     * 
     * @param ArchivoService $archivoService
     */
    public function __construct(ArchivoService $archivoService)
    {
        $this->archivoService = $archivoService;
    }

    /**
     * Listar archivos de una actividad.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad de la cual listar archivos
     * @return mixed
     * @throws \Exception
     */
    public function list(Proyecto $proyecto, Actividad $actividad):mixed
    {
        try {
            return $this->archivoService->list($proyecto, $actividad);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), (int)$e->getCode() ?: 500);
        }
    }

    /**
     * Subir un archivo para una actividad específica.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que se asociará el archivo
     * @param ArchivoRequest $request Datos del archivo validados
     * @return mixed
     * @throws \Exception   
     */
    public function create(Proyecto $proyecto, Actividad $actividad, ArchivoRequest $request): mixed
    {
        try {
            return $this->archivoService->create($proyecto, $actividad, $request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Eliminar un archivo.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que pertenece el archivo
     * @param Archivo $archivo Archivo a eliminar
     * @return mixed
     * @throws \Exception
     */
    public function delete(Proyecto $proyecto, Actividad $actividad, Archivo $archivo): mixed
    {
        try {
            return $this->archivoService->delete($proyecto, $actividad, $archivo);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
