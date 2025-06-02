<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\TipoDocumentoRepositoryInterface;
use App\Models\TipoDocumento;

class TipoDocumentoRepository extends BaseEloquentRepository implements TipoDocumentoRepositoryInterface
{

    public function __construct(TipoDocumento $model)
    {
        parent::__construct($model);
    }
}
