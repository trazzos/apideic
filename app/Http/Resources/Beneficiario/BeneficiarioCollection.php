<?php

namespace App\Http\Resources\Beneficiario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BeneficiarioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           "data" => BeneficiarioResource::collection($this->collection)
        ];
    }
}
