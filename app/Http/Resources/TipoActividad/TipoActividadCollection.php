<?php

namespace App\Http\Resources\TipoActividad;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TipoActividadCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => TipoActividadResource::collection($this->collection)
        ];
    }
}
