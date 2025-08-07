<?php

namespace App\Dtos\Persona;

use App\Http\Requests\Persona\ActualizarCuentaRequest;

class ActualizarCuentaDto
{
    public function __construct(
        public readonly string $email,
        public readonly array $roles = [],
        public readonly ?string $password = null,
        public readonly ?string $currentPassword = null
    ) {}

    /** 
     * Crear DTO desde Request validado.
     */
    public static function fromRequest(ActualizarCuentaRequest $request): self
    {
        return new self(
            email: $request->get('email'),
            roles: $request->get('roles', []),
            password: $request->get('password'),
            currentPassword: $request->get('current_password')
        );
    }

    /**
     * Verificar si se debe actualizar el email.
     */
    public function shouldUpdateEmail(): bool
    {
        return !empty($this->email);
    }

    /**
     * Verificar si se debe actualizar la contraseña.
     */
    public function shouldUpdatePassword(): bool
    {
        return !empty($this->password);
    }

    /**
     * Obtener datos para actualizar el usuario.
     * @return array<string, mixed>
     */
    public function toUserUpdateArray(): array
    {
        $data = [];

        if ($this->shouldUpdateEmail()) {
            $data['email'] = $this->email;
        }

        if ($this->shouldUpdatePassword()) {
            $data['password'] = bcrypt($this->password);
        }

        return $data;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
