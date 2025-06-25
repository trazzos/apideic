<?php

namespace App\Http\Resources\Capacitador;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CapacitadorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => CapacitadorResource::collection($this->collection)
        ];
    }
}
