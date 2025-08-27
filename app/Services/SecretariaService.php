<?php

namespace App\Services;

use App\Repositories\Eloquent\SecretariaRepository;

class SecretariaService extends BaseService {

    public function __construct(private readonly SecretariaRepository $secretariaRepository)
    {
        $this->repository = $this->secretariaRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Secretaria\\SecretariaCollection";
        $this->customResource = "App\\Http\\Resources\\Secretaria\\SecretariaResource";
    }
}
