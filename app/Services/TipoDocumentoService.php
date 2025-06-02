<?php

namespace App\Services;

use App\Repositories\Eloquent\TipoDocumentoRepository;

class TipoDocumentoService extends BaseService {

    public function __construct(readonly TipoDocumentoRepository $tipoDocumentoRepository)
    {
        $this->repository = $this->tipoDocumentoRepository;
    }
}
