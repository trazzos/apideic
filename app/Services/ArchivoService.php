<?php

namespace App\Services;

use App\Http\Requests\Shared\ArchivoRequest;
use App\Interfaces\Repositories\ArchivoRepositoryInterface;
use App\Interfaces\Repositories\TipoDocumentoRepositoryInterface;
use App\Models\Actividad;
use App\Models\Proyecto;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Servicio para la gestión de documentos de actividades.
 * 
 * Este servicio maneja la lógica de negocio para subir, listar y eliminar
 * documentos asociados a actividades, incluyendo el almacenamiento físico
 * de archivos y la persistencia en base de datos.
 * 
 * @package App\Services
 */
class ArchivoService
{
    /**
     * Repositorio para operaciones de base de datos de archivos.
     */
    private readonly ArchivoRepositoryInterface $archivoRepository;
    
    /**
     * Repositorio para operaciones de base de datos de tipos de documento.
     */
    private readonly TipoDocumentoRepositoryInterface $tipoDocumentoRepository;

    /**
     * Clase del recurso de colección personalizada.
     */
    private string $customResourceCollection = "App\\Http\\Resources\\Archivo\\ArchivoCollection";
    
    /**
     * Clase del recurso individual personalizada.
     */
    private string $customResource = "App\\Http\\Resources\\Archivo\\ArchivoResource";

    /**
     * Constructor del servicio de documentos.
     * 
     * @param ArchivoRepositoryInterface $archivoRepository Repositorio para operaciones de archivos
     * @param TipoDocumentoRepositoryInterface $tipoDocumentoRepository Repositorio para tipos de documento
     */
    public function __construct(ArchivoRepositoryInterface $archivoRepository, 
        TipoDocumentoRepositoryInterface $tipoDocumentoRepository)
    {
        $this->archivoRepository = $archivoRepository;
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
    }

    /**
     * Subir un documento para una actividad específica.
     * 
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que se asociará el documento
     * @param ArchivoRequest $request Datos del documento validados
     * @return JsonResource Recurso JSON del documento creado
     * @throws \Exception Si hay error en la subida o validación
     */
    public function create(Proyecto $proyecto, Actividad $actividad, ArchivoRequest $request): JsonResource
    {
        // Validar que la actividad pertenece al proyecto
        if ($actividad->proyecto_id !== $proyecto->id) {
            throw new \Exception('La actividad no pertenece al proyecto especificado', 404);
        }

        $file = $request->file('archivo');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Generar nombre único para el archivo
        $fileName = Str::uuid() . '.' . $extension;
        
        // Guardar archivo en storage
        $path = $file->storeAs(
            'documentos/actividades/' . $actividad->uuid,
            $fileName,
            'public'
        );

        // tipo_documento_nombre es derivado del catalogo tipos_documento que se accede con el tipo_documento_id
        // Preparar datos para el repositorio
        $archivoData = [
            'uuid' => Str::uuid(),
            'nombre_original' => $originalName,
            'ruta' => $path,
            'extension' => $extension,
            'tamanio' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'tipo_documento_id' => $request->tipo_documento_id,
            'tipo_documento_nombre' => $this->getTipoDocumentoNombre($request->tipo_documento_id),
            'archivable_id' => $actividad->id,
            'archivable_type' => Actividad::class,
        ];

        $archivo = $this->archivoRepository->create($archivoData);
        if ($this->customResource) {
            return new $this->customResource($archivo);
        }

        return JsonResource::make($archivo);
    }

    /**
     * Listar archivos de una actividad.
     * 
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad de la cual listar archivos
     * @return mixed Colección de archivos
     * @throws \Exception Si la actividad no pertenece al proyecto
     */
    public function list(Proyecto $proyecto, Actividad $actividad):mixed
    {
        // Validar que la actividad pertenece al proyecto
        if ($actividad->proyecto_id !== $proyecto->id) {
            throw new \Exception('La actividad no pertenece al proyecto especificado', 404);
        }

        $archivos = $this->archivoRepository->findByArchivableId($actividad->id);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($archivos);
        }

        return ResourceCollection::make($archivos);
    }

    /**
     * Eliminar un archivo.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que pertenece el archivo
     * @param Archivo $archivo Archivo a eliminar
     * @return array Respuesta de confirmación
     * @throws \Exception Si hay errores de validación o no se encuentra el archivo
     */
    public function delete(Proyecto $proyecto, Actividad $actividad, Archivo $archivo): array
    {
        // Validar que la actividad pertenece al proyecto
        if ($actividad->proyecto_id !== $proyecto->id) {
            throw new \Exception('La actividad no pertenece al proyecto especificado', 404);
        }
        // Validar que el archivo es de tipo Actividad
        if ($archivo->archivable_type !== Actividad::class) {
            throw new \Exception('El archivo no es un documento de actividad', 400);
        }       
        //validar que el archivo pertenece a la actividad
        if ($archivo->archivable_id !== $actividad->id) {
            throw new \Exception('El archivo no pertenece a la actividad especificada', 404);
        }

        // Buscar el documento en el repositorio
        $archivoEncontrado = $this->archivoRepository->findByUuid($archivo->uuid);

        if (!$archivoEncontrado) {
            throw new \Exception('Documento no encontrado', 404);
        }

        // Eliminar archivo físico
        Storage::disk('public')->delete($archivoEncontrado->ruta);
        
        // Eliminar registro de la base de datos
        $this->archivoRepository->delete($archivoEncontrado->id);

        return response()->noContent();
    }

    /**
     * Descargar un archivo específico de una actividad.
     *
     * @param Proyecto $proyecto Proyecto al que pertenece la actividad
     * @param Actividad $actividad Actividad a la que pertenece el archivo
     * @param Archivo $archivo Archivo a descargar
     * @return \Symfony\Component\HttpFoundation\StreamedResponse Respuesta de descarga del archivo
     * @throws \Exception Si hay errores de validación o no se encuentra el archivo
     */
    public function download(Proyecto $proyecto, Actividad $actividad, Archivo $archivo): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Validar que la actividad pertenece al proyecto
        if ($actividad->proyecto_id !== $proyecto->id) {
            throw new \Exception('La actividad no pertenece al proyecto especificado', 404);
        }

        // Validar que el archivo es de tipo Actividad
        if ($archivo->archivable_type !== Actividad::class) {
            throw new \Exception('El archivo no es un documento de actividad', 400);
        }

        // Validar que el archivo pertenece a la actividad
        if ($archivo->archivable_id !== $actividad->id) {
            throw new \Exception('El archivo no pertenece a la actividad especificada', 404);
        }

        // Verificar que el archivo existe físicamente
        if (!Storage::disk('public')->exists($archivo->ruta)) {
            throw new \Exception('El archivo físico no se encuentra en el almacenamiento', 404);
        }

        // Obtener el contenido del archivo
        $fileContent = Storage::disk('public')->get($archivo->ruta);
        $fileName = $archivo->nombre_original;
        $mimeType = $archivo->mime_type;

        // Crear respuesta de descarga
        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $fileName, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($fileContent),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    /**
     * Obtener el nombre del tipo de documento basado en el ID.
     * 
     * @param int $tipoDocumentoId ID del tipo de documento
     * @return string Nombre del tipo de documento
     */
    private function getTipoDocumentoNombre(int $tipoDocumentoId): string
    {
        $tipoDocumento = $this->tipoDocumentoRepository->findById($tipoDocumentoId);
        return $tipoDocumento->nombre;
    }
}
