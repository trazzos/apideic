<?php

namespace App\Http\Requests\TipoActividad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoActividadPutRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required','string', Rule::unique('tipos_actividad', 'nombre')->ignore($this->tipoActividad->id)],
            'descripcion' => ['required','string'],
            'mostrar_en_calendario' => ['required','string'],
        ];
    }
}
