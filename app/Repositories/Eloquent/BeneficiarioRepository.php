<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\BeneficiarioRepositoryInterface;
use App\Models\Beneficiario;

class BeneficiarioRepository extends BaseEloquentRepository implements BeneficiarioRepositoryInterface
{

    public function __construct(Beneficiario $model)
    {
        parent::__construct($model);
    }
}
