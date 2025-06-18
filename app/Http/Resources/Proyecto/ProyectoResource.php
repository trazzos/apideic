<?php

namespace App\Http\Resources\Proyecto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "tipo_proyecto_id" => $this->tipo_proyecto_id,
            "departamento_id" => $this->departamento_id,
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
        ];
    }
}
