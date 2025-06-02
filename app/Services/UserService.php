<?php

namespace App\Services;
use App\Actions\Fortify\CreateNewUser;
use App\Dtos\User\CreateRoleDto;
use App\Models\Persona;
use App\Models\User;
class UserService extends CreateNewUser
{
    public function __construct()
    {

    }

    /**
     * @param CreateRoleDto $createUserDto
     * @param Persona|null $persona
     * @return User
     */
    public function crearUser(CreateRoleDto $createUserDto, ?Persona $persona = null): User {

        $userData = [
            'name' => $createUserDto->name ?? $createUserDto->email,
            'email' => $createUserDto->email,
            'password' => $createUserDto->password,
        ];

        $user = $this->create($userData);

        if ($persona) {
            $user->user()->associate($persona);
            $user->save();
        }
        return $user;
    }
}
