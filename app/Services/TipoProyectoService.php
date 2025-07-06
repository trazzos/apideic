<?php

namespace App\Services;

use App\Repositories\Eloquent\TipoProyectoRepository;

class TipoProyectoService extends BaseService {

    public function __construct(private readonly TipoProyectoRepository $tipoProyectoRepository)
    {
        $this->repository = $this->tipoProyectoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\TipoProyecto\\TipoProyectoCollection";
        $this->customResource = "App\\Http\\Resources\\TipoProyecto\\TipoProyectoResource";
    }
}
