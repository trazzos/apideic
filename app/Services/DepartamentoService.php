<?php

namespace App\Services;

use App\Repositories\Eloquent\DepartamentoRepository;

class DepartamentoService extends BaseService {

    public function __construct(readonly DepartamentoRepository $departamentoRepository)
    {
        $this->repository = $this->departamentoRepository;
    }
}
