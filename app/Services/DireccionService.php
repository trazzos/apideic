<?php

namespace App\Services;

use App\Repositories\Eloquent\DireccionRepository;
use App\Services\Traits\Searchable;

class DireccionService extends BaseService {

    use Searchable;
    
    public function __construct(private readonly DireccionRepository $direccionRepository)
    {
        $this->repository = $this->direccionRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Direccion\\DireccionCollection";
        $this->customResource = "App\\Http\\Resources\\Direccion\\DireccionResource";
    }
}
