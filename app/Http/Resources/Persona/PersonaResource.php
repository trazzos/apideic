<?php

namespace App\Http\Resources\Persona;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonaResource extends JsonResource
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
            'dependencia_type' => $this->tipo_dependencia,
            'dependencia_id' => $this->dependencia_id,
            'nombre_dependencia' => $this->dependencia->nombre ?? null,
            'nombre' => $this->nombre,
            'apellido_paterno' => $this->apellido_paterno,
            'apellido_materno' => $this->apellido_materno,
            'es_titular' => $this->es_titular,
            'url_fotografia' => $this->url_fotografia,
            'email' => $this->whenLoaded('user', $this->user?->email),
            'cuenta_activa' => $this->whenLoaded('user', $this->user?->active),
            'estatus' => $this->estatus,
        ];
    }
}
