<?php

namespace App\Services;

use App\Repositories\Eloquent\UnidadApoyoRepository;
use App\Services\Traits\Searchable;

class UnidadApoyoService extends BaseService {

    use Searchable;

    public function __construct(private readonly UnidadApoyoRepository $unidadApoyoRepository)
    {
        $this->repository = $this->unidadApoyoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\UnidadApoyo\\UnidadApoyoCollection";
        $this->customResource = "App\\Http\\Resources\\UnidadApoyo\\UnidadApoyoResource";
    }

    public function getBySecretaria(int $secretariaId)
    {
        return $this->unidadApoyoRepository->findBySecretaria($secretariaId);
    }
}
