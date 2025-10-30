<?php

namespace App\Http\Requests\Proyecto;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TipoProyecto;

class ProyectoPostRequest extends FormRequest
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
            'tipo_proyecto_id' => ['required','integer', Rule::exists('tipos_proyecto', 'id')],
            'departamento_id' => ['required','integer', Rule::exists('departamentos', 'id')],
            'nombre' => ['required','string'],
            'descripcion' => ['required','string'],
            'monto' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
                function ($attribute, $value, $fail) {
                    if ($this->esProyectoInversion() && empty($value)) {
                        $fail('El campo monto es requerido para proyectos de inversión.');
                    }
                }
            ],
        ];
    }

    /**
     * Verificar si el tipo de proyecto es de inversión
     */
    private function esProyectoInversion(): bool
    {
        $tipoProyectoId = $this->input('tipo_proyecto_id');
        if (!$tipoProyectoId) {
            return false;
        }

        $tipoProyecto = TipoProyecto::find($tipoProyectoId);
        return $tipoProyecto && strtolower($tipoProyecto->nombre) === 'inversion';
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'monto.required' => 'El campo monto es requerido para proyectos de inversión.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto debe ser mayor o igual a 0.',
            'monto.max' => 'El monto no puede exceder el valor máximo permitido.',
            'tipo_proyecto_id.required' => 'El tipo de proyecto es requerido.',
            'tipo_proyecto_id.exists' => 'El tipo de proyecto seleccionado no existe.',
            'departamento_id.required' => 'El departamento es requerido.',
            'departamento_id.exists' => 'El departamento seleccionado no existe.',
            'nombre.required' => 'El nombre del proyecto es requerido.',
            'descripcion.required' => 'La descripción del proyecto es requerida.',
        ];
    }
}
