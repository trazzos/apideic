<?php

namespace App\Dtos\Proyecto;

use Illuminate\Http\Request;

class UpdateProyectoDto
{

    /*
     * @param int $tipoProyectoId
     * @param int $departamentoId
     * @param string $nombre
     * @param string $descripcion
     */
    public function __construct(
        public readonly int $tipoProyectoId,
        public readonly int $departamentoId,
        public readonly string $nombre,
        public readonly string $descripcion,
    )
    {

    }

    /*
     * @param Request $request
     * @return UpdateProyectoDto
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('tipo_proyecto_id'),
            $request->input('departamento_id'),
            $request->input('nombre'),
            $request->input('descripcion'),
        );
    }
}
