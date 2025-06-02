<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ProyectoRepositoryInterface;
use App\Models\Proyecto;

class ProyectoRepository extends BaseEloquentRepository implements ProyectoRepositoryInterface
{

    public function __construct(Proyecto $model)
    {
        parent::__construct($model);
    }
}
