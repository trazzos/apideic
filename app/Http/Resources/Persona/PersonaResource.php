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
            'public_url_fotografia' => $this->url_fotografia ? $this->getPublicUrlFotografia() : null,
            'email' => $this->whenLoaded('user', $this->user?->email),
            'cuenta_activa' => $this->whenLoaded('user', $this->user?->active),
            'estatus' => $this->estatus,
        ];
    }

    /**
     * Obtener la URL pública de la fotografía accesible vía HTTP.
     * 
     * @return string
     */
    private function getPublicUrlFotografia(): string
    {
        // Si la URL ya contiene el dominio, devolverla tal como está
        if (str_starts_with($this->url_fotografia, 'http') || str_starts_with($this->url_fotografia, 'https')) {
            return $this->url_fotografia;
        }

        // Remover barras iniciales para evitar duplicados
        $path = ltrim($this->url_fotografia, '/');
        
        // Construir la URL pública usando la configuración del disco público
        // que apunta a public/storage gracias al enlace simbólico
        return url('storage/' . $path);
    }
}
