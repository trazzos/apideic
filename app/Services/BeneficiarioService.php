<?php

namespace App\Services;

use App\Repositories\Eloquent\BeneficiarioRepository;

class BeneficiarioService extends BaseService {

    public function __construct(private readonly BeneficiarioRepository $beneficiarioRepository)
    {
        $this->repository = $this->beneficiarioRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Beneficiario\\BeneficiarioCollection";
        $this->customResource = "App\\Http\\Resources\\Beneficiario\\BeneficiarioResource";
    }
}
