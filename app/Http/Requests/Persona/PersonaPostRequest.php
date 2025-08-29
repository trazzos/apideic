<?php

namespace App\Http\Requests\Persona;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
            'dependencia_type' => [
                'required', 
                'string',
                Rule::in(['Secretaria', 'Subsecretaria', 'Direccion', 'Departamento'])
            ],
            'dependencia_id' => [
                'required', 
                'integer',
                function ($attribute, $value, $fail) {
                    $type = $this->input('dependencia_type');
                    $table = match($type) {
                        'Secretaria' => 'secretarias',
                        'Subsecretaria' => 'subsecretarias',
                        'Direccion' => 'direcciones',
                        'Departamento' => 'departamentos',
                        default => null
                    };
                    
                    if ($table && !DB::table($table)->where('id', $value)->exists()) {
                        $fail("La {$type} seleccionada no existe.");
                    }
                }
            ],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'es_titular' => ['string', Rule::in(['Si', 'No'])],
            'fotografia' => ['nullable', 'image', 'max:2048'],
            'email' => ['nullable', 'string','email', 'unique:users,email'],
            'password' => ['nullable','string','min:8', 'required_with:email'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'dependencia_type.required' => 'El tipo de dependencia es requerido.',
            'dependencia_type.in' => 'El tipo de dependencia debe ser: Secretaria, Subsecretaria, Direccion o Departamento.',
            'dependencia_id.required' => 'Debe seleccionar una dependencia.',
            'dependencia_id.integer' => 'El ID de la dependencia debe ser un número.',
            'es_titular.in' => 'El campo titular debe ser "Si" o "No".',
        ];
    }
}
