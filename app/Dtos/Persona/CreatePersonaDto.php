<?php

namespace App\Dtos\Persona;

use Illuminate\Http\Request;


class CreatePersonaDto
{
    /**
     * @param string $dependenciaType Tipo de dependencia (Secretaria, Subsecretaria, Direccion, Departamento)
     * @param int $dependenciaId ID de la dependencia
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param string $esTitular Si la persona es titular de la dependencia ("Si"/"No")
     * @param string|null $email
     * @param string|null $password
     */
    public function __construct(
        public readonly string $dependenciaType,
        public readonly int $dependenciaId,
        public readonly string $nombre,
        public readonly string $apellidoPaterno,
        public readonly string $apellidoMaterno,
        public readonly string $esTitular = 'No',
        public readonly ?string $email = null,
        public readonly ?string $password = null,
    )
    {
    }

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        // Convertir boolean a string Si/No si viene como boolean
        $esTitular = $request->input('es_titular');
        if (is_bool($esTitular)) {
            $esTitular = $esTitular ? 'Si' : 'No';
        } elseif ($esTitular === null) {
            $esTitular = 'No';
        }

        return new self(
            $request->input('dependencia_type'),
            $request->input('dependencia_id'),
            $request->input('nombre'),
            $request->input('apellido_paterno'),
            $request->input('apellido_materno'),
            $esTitular,
            $request->input('email'),
            $request->input('password')
        );
    }

    /**
     * Convertir el tipo de dependencia string al modelo correspondiente.
     */
    public function getDependenciaModel(): string
    {
        return match(strtolower($this->dependenciaType)) {
            'secretaria' => \App\Models\Secretaria::class,
            'subsecretaria' => \App\Models\Subsecretaria::class,
            'direccion' => \App\Models\Direccion::class,
            'departamento' => \App\Models\Departamento::class,
            'unidad_apoyo' => \App\Models\UnidadApoyo::class,
            default => throw new \InvalidArgumentException("Tipo de dependencia inválido: {$this->dependenciaType}")
        };
    }
}
