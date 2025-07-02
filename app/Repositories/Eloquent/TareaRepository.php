<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\TareaRepositoryInterface;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Collection;

class TareaRepository extends BaseEloquentRepository implements TareaRepositoryInterface
{

    public function __construct(Tarea $model)
    {
        parent::__construct($model);
    }

    public function findByActividadUuid(string $uuid): Collection
    {
        return $this->model->where('actividad_uuid', $uuid)->get();
    }
}
