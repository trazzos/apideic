<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ActividadRepositoryInterface;
use App\Models\Actividad;

class ActividadRepository extends BaseEloquentRepository implements ActividadRepositoryInterface
{

    public function __construct(Actividad $model)
    {
        parent::__construct($model);
    }

    public function findByProyectoUuid(string $uuid): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->whereHas('proyecto', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->get();
    }
}
