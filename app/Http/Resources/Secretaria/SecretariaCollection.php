<?php

namespace App\Http\Resources\Secretaria;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SecretariaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => SecretariaResource::collection($this->collection)
        ];
    }
}
