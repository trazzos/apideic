<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CatalogoRepositoryInterface;
use App\Models\Secretaria;

class SecretariaRepository extends BaseEloquentRepository implements CatalogoRepositoryInterface
{

    public function __construct(Secretaria $model)
    {
        parent::__construct($model);
    }
}
