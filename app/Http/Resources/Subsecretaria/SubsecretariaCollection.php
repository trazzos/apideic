<?php

namespace App\Http\Resources\Subsecretaria;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubsecretariaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => SubsecretariaResource::collection($this->collection)
        ];
    }
}
