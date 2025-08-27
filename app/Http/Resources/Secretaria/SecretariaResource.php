<?php

namespace App\Http\Resources\Secretaria;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretariaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ];
    }
}
