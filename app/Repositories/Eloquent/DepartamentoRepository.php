<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ProyectoRepositoryInterface;
use App\Models\Departamento;

class DepartamentoRepository extends BaseEloquentRepository implements ProyectoRepositoryInterface
{

    public function __construct(Departamento $model)
    {
        parent::__construct($model);
    }
}
