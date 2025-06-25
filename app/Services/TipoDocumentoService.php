<?php

namespace App\Services;

use App\Repositories\Eloquent\TipoDocumentoRepository;

class TipoDocumentoService extends BaseService {

    public function __construct(readonly TipoDocumentoRepository $tipoDocumentoRepository)
    {
        $this->repository = $this->tipoDocumentoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\TipoDocumento\\TipoDocumentoCollection";
        $this->customResource = "App\\Http\\Resources\\TipoDocumento\\TipoDocumentoResource";
    }
}
