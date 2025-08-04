<?php

namespace App\Http\Requests\Role;

use App\Rules\UniqueRoleName;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class RolePutRequest extends FormRequest
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
            'nombre' => ['required','string','min:3','max:255', new UniqueRoleName()],
            'permisos' => ['nullable','array'],
            'permisos.*' => ['exists:permissions,name']
        ];
    }
}
