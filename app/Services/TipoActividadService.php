<?php

namespace App\Services;

use App\Repositories\Eloquent\TipoActividadRepository;

class TipoActividadService extends BaseService {

    public function __construct(readonly TipoActividadRepository $tipoActividadRepository)
    {
        $this->repository = $this->tipoActividadRepository;
    }
}
