<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\TipoProyectoRepositoryInterface;
use App\Models\TipoProyecto;

class TipoProyectoRepository extends BaseEloquentRepository implements TipoProyectoRepositoryInterface
{

    public function __construct(TipoProyecto $model)
    {
        parent::__construct($model);
    }
}
