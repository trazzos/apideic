<?php

namespace App\Dtos\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateRoleDto
{
    public function __construct(
         public readonly string $title,
         public readonly string $name,
         public readonly array $permisos,
    ) {
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->input('nombre'),
            Str::slug($request->input('nombre'), '_'),
            $request->input('permisos') ?? []
        );
    }
}
