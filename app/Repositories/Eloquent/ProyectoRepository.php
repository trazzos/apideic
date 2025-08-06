<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ProyectoRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Models\Proyecto;
use App\Repositories\Traits\Searchable;

class ProyectoRepository extends BaseEloquentRepository implements ProyectoRepositoryInterface, SearchableRepositoryInterface
{
    use  Searchable;
    /**
     * @param Proyecto $model
     */
    public function __construct(Proyecto $model)
    {
        parent::__construct($model);
    }
}
