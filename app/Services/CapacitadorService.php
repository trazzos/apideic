<?php

namespace App\Services;

use App\Repositories\Eloquent\CapacitadorRepository;

class CapacitadorService extends BaseService {

    public function __construct(readonly CapacitadorRepository $capacitadorRepository)
    {
        $this->repository = $this->capacitadorRepository;
    }
}
