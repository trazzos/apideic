<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ArchivoRepositoryInterface;
use App\Models\Archivo;
use App\Models\Actividad;

/**
 * Repositorio para la gestión de archivos/documentos.
 * 
 * @package App\Repositories\Eloquent
 */
class ArchivoRepository extends BaseEloquentRepository implements ArchivoRepositoryInterface
{
    /**
     * Constructor del repositorio de archivos.
     * 
     * @param Archivo $archivo
     */
    public function __construct(Archivo $archivo)
    {
        parent::__construct($archivo);
    }

    /**
     * Buscar archivos por actividad.
     * 
     * @param int $archivableId ID de la actividad
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByArchivableId(int $archivableId)
    {
        return $this->model->where('archivable_id', $archivableId)
                    ->where('archivable_type', Actividad::class)
                    ->get();
    }
}
