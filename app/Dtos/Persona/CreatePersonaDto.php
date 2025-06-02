<?php

namespace App\Dtos\Persona;

use Illuminate\Http\Request;


class CreatePersonaDto
{

    /**
     * @param int $departamentoId
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param string $responsableDepartamento
     */
    public function __construct(
        public readonly int $departamentoId,
        public readonly string $nombre,
        public readonly string $apellidoPaterno,
        public readonly string $apellidoMaterno,
        public readonly string $responsableDepartamento,
        public readonly ?string $email,
        public readonly ?string $passsword,
    )
    {

    }

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('departamento_id'),
            $request->input('nombre'),
            $request->input('apellido_paterno'),
            $request->input('apellido_materno'),
            $request->input('responsable_departamento'),
            $request->input('email'),
            $request->input('password')
        );
    }
}
