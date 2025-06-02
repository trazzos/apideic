<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\TipoActividadRepositoryInterface;
use App\Models\TipoActividad;

class TipoActividadRepository extends BaseEloquentRepository implements TipoActividadRepositoryInterface
{

    public function __construct(TipoActividad $model)
    {
        parent::__construct($model);
    }
}
