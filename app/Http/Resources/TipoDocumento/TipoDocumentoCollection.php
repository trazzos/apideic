<?php

namespace App\Http\Resources\TipoDocumento;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TipoDocumentoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => TipoDocumentoResource::collection($this->collection)
        ];
    }
}
