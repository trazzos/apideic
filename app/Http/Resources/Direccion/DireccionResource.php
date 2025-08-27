<?php

namespace App\Http\Resources\Direccion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Subsecretaria\SubsecretariaResource;

class DireccionResource extends JsonResource
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
            'subsecretaria' => $this->whenLoaded('subsecretaria', SubsecretariaResource::make($this->subsecretaria)),
        ];
    }
}
