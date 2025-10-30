<?php

namespace App\Dtos\Proyecto;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CreateProyectoDto
{

    /*
     * @param int $tipoProyectoId
     * @param int $departamentoId
     * @param string $nombre
     * @param string $descripcion
     * @param float|null $monto
     * @param string $uuid
     */
    public function __construct(
        public readonly int $tipoProyectoId,
        public readonly int $departamentoId,
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly ?float $monto,
        public readonly string $uuid
    )
    {

    }

    /*
     * @param Request $request
     * @return CreateProyectoDto
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('tipo_proyecto_id'),
            $request->input('departamento_id'),
            $request->input('nombre'),
            $request->input('descripcion'),
            $request->input('monto'),
            Str::uuid()
        );
    }
}
