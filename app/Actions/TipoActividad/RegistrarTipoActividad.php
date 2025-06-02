<?php

namespace App\Actions\TipoActividad;

use App\Http\Requests\TipoActividad\TipoActividadPostRequest;
use App\Services\TipoActividadService;

class RegistrarTipoActividad
{
    public function __construct(private readonly TipoActividadService $tipoActividadService)
    {}

    public function registrar(array $data)
    {
        $this->tipoActividadService->create($data);
    }
}
