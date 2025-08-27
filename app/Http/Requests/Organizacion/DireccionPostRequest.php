<?php

namespace App\Http\Requests\Organizacion;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DireccionPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subsecretaria_id' => [
                'required',
                'exists:subsecretarias,id',
            ],
            'nombre' => [
                'required',
                'string',
                Rule::unique('direcciones', 'nombre')
                ->where(fn($query) => $query->where('subsecretaria_id', $this->subsecretaria_id))
            ],
            'descripcion' => ['required','string'],
        ];
    }
}
