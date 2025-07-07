<?php

namespace App\Services;

use App\Interfaces\Repositories\TipoDocumentoRepositoryInterface;

class TipoDocumentoService extends BaseService {

    public function __construct(private readonly TipoDocumentoRepositoryInterface $tipoDocumentoRepository)
    {
        $this->repository = $this->tipoDocumentoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\TipoDocumento\\TipoDocumentoCollection";
        $this->customResource = "App\\Http\\Resources\\TipoDocumento\\TipoDocumentoResource";
    }
}
