<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CapacitadorRepositoryInterface;
use App\Models\Capacitador;

class CapacitadorRepository extends BaseEloquentRepository implements CapacitadorRepositoryInterface
{

    public function __construct(Capacitador $model)
    {
        parent::__construct($model);
    }
}
