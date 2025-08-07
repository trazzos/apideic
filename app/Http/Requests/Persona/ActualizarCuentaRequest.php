<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ActualizarCuentaRequest extends FormRequest
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
        $persona = $this->route('persona');
        
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . ($persona->user->id ?? 'NULL')
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => [
                'required_with:password'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Este email ya está siendo utilizado por otro usuario.',
            'email.email' => 'Debe proporcionar una dirección de email válida.',
            'email.required' => 'El email es requerido para crear o actualizar la cuenta.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.required' => 'La contraseña es requerida para crear o actualizar la cuenta.',
            'current_password.required_with' => 'Debe proporcionar la contraseña actual para cambiarla.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'current_password' => 'contraseña actual',
        ];
    }
}
