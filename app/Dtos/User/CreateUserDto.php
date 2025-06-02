<?php

namespace App\Dtos\User;

class CreateUserDto
{
    public function __construct(
         public readonly string $email,
         public readonly string $password,
         public readonly ?string $name = null,
         public readonly ?int $personaId = null
    ) {
    }
}
