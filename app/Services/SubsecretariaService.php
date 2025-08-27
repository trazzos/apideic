<?php

namespace App\Services;

use App\Repositories\Eloquent\SubsecretariaRepository;
use App\Services\Traits\Searchable;

class SubsecretariaService extends BaseService {

    use Searchable;

    public function __construct(private readonly SubsecretariaRepository $subsecretariaRepository)
    {
        $this->repository = $this->subsecretariaRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Subsecretaria\\SubsecretariaCollection";
        $this->customResource = "App\\Http\\Resources\\Subsecretaria\\SubsecretariaResource";
    }
}
