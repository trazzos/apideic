<?php

namespace App\Services;

use App\Repositories\Interfaces\TipoDocumentoRepositoryInterface;

class TipoDocumentoService extends BaseService {

    public function __construct(private readonly TipoDocumentoRepositoryInterface $tipoDocumentoRepository)
    {
        $this->repository = $this->tipoDocumentoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\TipoDocumento\\TipoDocumentoCollection";
        $this->customResource = "App\\Http\\Resources\\TipoDocumento\\TipoDocumentoResource";
    }
}
