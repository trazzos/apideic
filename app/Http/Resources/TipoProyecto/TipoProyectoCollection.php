<?php

namespace App\Http\Resources\TipoProyecto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TipoProyectoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => TipoProyectoResource::collection($this->collection)
        ];
    }
}
