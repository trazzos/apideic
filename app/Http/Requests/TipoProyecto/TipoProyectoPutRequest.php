<?php

namespace App\Http\Requests\TipoProyecto;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoProyectoPutRequest extends FormRequest
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
            'nombre' => ['required','string', Rule::unique('tipos_proyecto')->ignore($this->tiposProyecto->id)],
            'descripcion' => ['required','string'],
        ];
    }
}
