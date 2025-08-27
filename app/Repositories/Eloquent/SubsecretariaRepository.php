<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CatalogoRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Models\Subsecretaria;
use App\Repositories\Traits\Searchable;

class SubsecretariaRepository extends BaseEloquentRepository implements CatalogoRepositoryInterface, SearchableRepositoryInterface
{

    use Searchable;

    public function __construct(Subsecretaria $model)
    {
        parent::__construct($model);
        $this->setSearchFields([
            'nombre',
        ]);
    }
}
