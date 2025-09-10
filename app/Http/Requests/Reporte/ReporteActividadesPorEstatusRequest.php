<?php

namespace App\Http\Requests\Reporte;

use Illuminate\Foundation\Http\FormRequest;

class ReporteActividadesPorEstatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajustar según lógica de permisos
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha_inicio' => 'nullable|date|before_or_equal:fecha_fin',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'tipo_proyecto_id' => 'nullable|integer|exists:tipos_proyecto,id',
            'estatus' => 'nullable|in:completado,en_curso,sin_iniciar',
            'departamento_id' => 'nullable|integer|exists:departamentos,id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'fecha_inicio.before_or_equal' => 'La fecha de inicio debe ser anterior o igual a la fecha fin.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser posterior o igual a la fecha de inicio.',
            'tipo_proyecto_id.exists' => 'El tipo de proyecto seleccionado no existe.',
            'estatus.in' => 'El estatus debe ser: completado, en_curso o sin_iniciar.',
            'departamento_id.exists' => 'El departamento seleccionado no existe.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'fecha_inicio' => 'Fecha de inicio',
            'fecha_fin' => 'Fecha fin',
            'tipo_proyecto_id' => 'Tipo de proyecto',
            'estatus' => 'Estatus',
            'departamento_id' => 'Departamento',
        ];
    }
}
