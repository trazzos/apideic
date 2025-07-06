<?php

namespace App\Interfaces\Repositories;

/**
 * Interfaz para el repositorio de archivos.
 * 
 * @package App\Interfaces\Repositories
 */
interface ArchivoRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Buscar archivos por actividad.
     * 
     * @param int $archivableId ID del recurso archivable
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByArchivableId(int $archivableId);
}
