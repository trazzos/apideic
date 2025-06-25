<?php

namespace App\Services;

use App\Repositories\Eloquent\AutoridadRepository;

class AutoridadService extends BaseService {

    public function __construct(readonly AutoridadRepository $autoridadRepository)
    {
        $this->repository = $this->autoridadRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Autoridad\\AutoridadCollection";
        $this->customResource = "App\\Http\\Resources\\Autoridad\\AutoridadResource";
    }
}
