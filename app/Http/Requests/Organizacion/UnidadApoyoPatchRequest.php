<?php

namespace App\Http\Requests\Organizacion;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnidadApoyoPatchRequest extends FormRequest
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
        $unidadApoyoId = $this->route('unidadApoyo')?->id;

        return [
            'secretaria_id' => [
                'required',
                'exists:secretarias,id',
            ],
            'nombre' => [
                'required',
                'string', 
                'max:255',
                Rule::unique('unidades_apoyo', 'nombre')
                ->where(fn($query) => $query->where('secretaria_id', $this->secretaria_id))
                ->ignore($unidadApoyoId)],
            'descripcion' => ['nullable','string'],
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'secretaria_id.required' => 'La secretaría es requerida.',
            'secretaria_id.exists' => 'La secretaría seleccionada no existe.',
            'nombre.required' => 'El nombre de la unidad de apoyo es requerido.',
            'nombre.unique' => 'Ya existe una unidad de apoyo con este nombre en la secretaría seleccionada.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
        ];
    }
}
