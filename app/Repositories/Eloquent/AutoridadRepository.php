<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\AutoridadRepositoryInterface;
use App\Models\Autoridad;

class AutoridadRepository extends BaseEloquentRepository implements AutoridadRepositoryInterface
{

    public function __construct(Autoridad $model)
    {
        parent::__construct($model);
    }
}
