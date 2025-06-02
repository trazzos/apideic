<?php

namespace App\Http\Requests\Proyecto;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProyectoPostRequest extends FormRequest
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
            'tipo_proyecto_id' => ['required','integer', Rule::exists('tipos_proyecto', 'id')],
            'departamento_id' => ['required','integer', Rule::exists('departamentos', 'id')],
            'nombre' => ['required','string'],
            'descripcion' => ['required','string'],
        ];
    }
}
