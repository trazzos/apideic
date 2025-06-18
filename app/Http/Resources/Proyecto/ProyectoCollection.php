<?php

namespace App\Http\Resources\Proyecto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProyectoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => ProyectoResource::collection($this->collection)
        ];
    }
}
