<?php

namespace App\Services;

use App\Repositories\Eloquent\DepartamentoRepository;

class DepartamentoService extends BaseService {

    public function __construct(private readonly DepartamentoRepository $departamentoRepository)
    {
        $this->repository = $this->departamentoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Departamento\\DepartamentoCollection";
        $this->customResource = "App\\Http\\Resources\\Departamento\\DepartamentoResource";
    }
}
