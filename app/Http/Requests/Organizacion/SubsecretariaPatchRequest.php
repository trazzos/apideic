<?php

namespace App\Http\Requests\Organizacion;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubsecretariaPatchRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string', 
                Rule::unique('subsecretarias', 'nombre')
                ->where(fn($query) => $query->where('secretaria_id', $this->route('secretaria')->id))
                ->ignore($this->route('subsecretaria')->id)],
            'descripcion' => ['required','string'],
        ];
    }
}
