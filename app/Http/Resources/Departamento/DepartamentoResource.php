<?php

namespace App\Http\Resources\Departamento;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Direccion\DireccionResource;

class DepartamentoResource extends JsonResource
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
            'direccion' => $this->whenLoaded('direccion', new DireccionResource($this->direccion)),
        ];
    }
}
