<?php

namespace App\Http\Resources\Archivo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchivoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'uuid' => $this->id,
            'nombre_original' => $this->nombre_original,
            'tipo' => $this->tipo_documento_nombre,
            'url' => $this->url,
            'tamanio' => $this->tamanio,
            'fecha_creacion' => $this->created_at->format('Y-m-d H:i:s'),
            'extension' => $this->extension,
            'mime_type' => $this->mime_type
        ];
    }
}
