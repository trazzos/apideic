<?php

namespace App\Services;

use App\Repositories\Eloquent\TipoProyectoRepository;

class TipoProyectoService extends BaseService {

    public function __construct(readonly TipoProyectoRepository $tipoProyectoRepository)
    {
        $this->repository = $this->tipoProyectoRepository;
    }
}
