<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\UnidadApoyoRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Models\UnidadApoyo;
use App\Repositories\Traits\Searchable;
use Illuminate\Database\Eloquent\Collection;

class UnidadApoyoRepository extends BaseEloquentRepository implements UnidadApoyoRepositoryInterface, SearchableRepositoryInterface
{

    use Searchable;

    public function __construct(UnidadApoyo $model)
    {
        parent::__construct($model);
        $this->setSearchFields([
            'nombre',
        ]);
    }

    public function findBySecretaria(int $secretariaId): Collection
    {
        return $this->model->where('secretaria_id', $secretariaId)->get();
    }
}
