<?php

namespace App\Http\Requests\Autoridad;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AutoridadPutRequest extends FormRequest
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
            'nombre' => ['required','string', Rule::unique('autoridades', 'nombre')->ignore($this->autoridad->id)],
            'descripcion' => ['required','string'],
        ];
    }
}
