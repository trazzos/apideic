<?php

namespace App\Dtos\Role;

use Illuminate\Http\Request;

class UpdateRoleDto
{
    public function __construct(
         public readonly string $tile,
         public readonly array $permisos,
    ) {
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->input('nombre'),
            $request->input('permisos') ?? []
        );
    }
}
