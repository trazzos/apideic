<?php

namespace App\Dtos\Persona;

use Illuminate\Http\Request;

class UpdatePersonaDto
{
    /**
     * @param string|null $dependenciaType Tipo de dependencia (Secretaria, Subsecretaria, Direccion, Departamento)
     * @param int|null $dependenciaId ID de la dependencia
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param string|null $esTitular Si la persona es titular de la dependencia ("Si"/"No")
     */
    public function __construct(
        public readonly ?string $dependenciaType,
        public readonly ?int $dependenciaId,
        public readonly string $nombre,
        public readonly string $apellidoPaterno,
        public readonly string $apellidoMaterno,
        public readonly ?string $esTitular = null,
    )
    { }

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        // Convertir boolean a string Si/No si viene como boolean
        $esTitular = null;
        if ($request->has('es_titular')) {
            $value = $request->input('es_titular');
            if (is_bool($value)) {
                $esTitular = $value ? 'Si' : 'No';
            } else {
                $esTitular = $value;
            }
        }

        return new self(
            $request->input('dependencia_type'),
            $request->input('dependencia_id'),
            $request->input('nombre'),
            $request->input('apellido_paterno'),
            $request->input('apellido_materno'),
            $esTitular,
        );
    }

    /**
     * Convertir el tipo de dependencia string al modelo correspondiente.
     */
    public function getDependenciaModel(): ?string
    {
        if (!$this->dependenciaType) {
            return null;
        }

        return match(strtolower($this->dependenciaType)) {
            'secretaria' => \App\Models\Secretaria::class,
            'subsecretaria' => \App\Models\Subsecretaria::class,
            'direccion' => \App\Models\Direccion::class,
            'departamento' => \App\Models\Departamento::class,
            'unidad_apoyo' => \App\Models\UnidadApoyo::class,
            default => throw new \InvalidArgumentException("Tipo de dependencia inválido: {$this->dependenciaType}")
        };
    }

    /**
     * Verificar si se debe actualizar la dependencia.
     */
    public function shouldUpdateDependencia(): bool
    {
        return $this->dependenciaType !== null && $this->dependenciaId !== null;
    }
}
