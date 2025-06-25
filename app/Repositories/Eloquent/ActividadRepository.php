<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\AutoridadRepositoryInterface;
use App\Models\Actividad;

class ActividadRepository extends BaseEloquentRepository implements AutoridadRepositoryInterface
{

    public function __construct(Actividad $model)
    {
        parent::__construct($model);
    }

    public function findByProyectoUuid(string $uuid)
    {
        return $this->model->whereHas('proyecto', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->get();
    }
}
