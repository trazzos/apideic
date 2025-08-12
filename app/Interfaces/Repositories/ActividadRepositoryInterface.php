<?php

namespace App\Interfaces\Repositories;

interface ActividadRepositoryInterface extends BaseRepositoryInterface,SearchableRepositoryInterface {

    public function findByProyectoUuid(string $uuid): \Illuminate\Database\Eloquent\Collection;
    
    public function getActividadesParaReporte(\App\Dtos\Reporte\ReporteActividadesDto $dto): \Illuminate\Database\Eloquent\Collection;
}
