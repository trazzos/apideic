<?php

namespace App\Http\Requests\Departamento;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartamentoPostRequest extends FormRequest
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
            'direccion_id' => ['required', 'exists:direcciones,id'],
            'nombre' => ['required','string', 
            Rule::unique('departamentos', 'nombre')
            ->where(fn($query) => $query->where('direccion_id', $this->direccion_id))],
            'descripcion' => ['required','string'],
        ];
    }
}
