<?php

namespace App\Http\Resources\Persona;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CuentaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'active' => $this->active,
            'roles' => $this->getRoleNames(),
        ];
    }
}
