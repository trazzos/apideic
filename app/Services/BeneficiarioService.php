<?php

namespace App\Services;

use App\Repositories\Eloquent\BeneficiarioRepository;

class BeneficiarioService extends BaseService {

    public function __construct(readonly BeneficiarioRepository $beneficiarioRepository)
    {
        $this->repository = $this->beneficiarioRepository;
    }
}
