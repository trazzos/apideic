<?php

namespace App\Http\Resources\Subsecretaria;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Secretaria\SecretariaResource;

class SubsecretariaResource extends JsonResource
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
            'secretaria' => $this->whenLoaded('secretaria', SecretariaResource::make($this->secretaria)),
        ];
    }
}
