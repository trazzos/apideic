<?php

namespace App\Http\Requests\Proyecto;

use App\Models\Actividad;
use App\Models\Proyecto;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TareaPostRequest extends FormRequest
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
            'nombre'=> ['required',Rule::unique('tareas')->where(function ($query) {
                return $query->where('actividad_uuid', $this->actividades->uuid);
            })],
        ];
    }



}
