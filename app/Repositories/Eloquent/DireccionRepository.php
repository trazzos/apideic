<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CatalogoRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Models\Direccion;
use App\Repositories\Traits\Searchable;

class DireccionRepository extends BaseEloquentRepository implements CatalogoRepositoryInterface, SearchableRepositoryInterface
{

    use Searchable;

    public function __construct(Direccion $model)
    {
        parent::__construct($model);
    }
}
