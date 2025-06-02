<?php

namespace App\Http\Requests\Persona;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonaPostRequest extends FormRequest
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
            'departamento_id' => ['required', 'integer', 'exists:departamentos,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'responsable_departamento' => ['required', 'string', 'max:255'],
            'fotografia' => ['nullable', 'image', 'max:2048'],
            'email' => ['nullable', 'string','email', 'unique:users,email'],
            'password' => ['nullable','string','min:8', 'required_with:email'],
        ];
    }
}
