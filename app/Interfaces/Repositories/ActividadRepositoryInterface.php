<?php

namespace App\Interfaces\Repositories;

interface ActividadRepositoryInterface extends BaseRepositoryInterface{

    public function findByProyectoUuid(string $uuid): \Illuminate\Database\Eloquent\Collection;
}
