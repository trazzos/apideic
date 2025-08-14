<?php

namespace App\Http\Requests\Proyecto;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActividadPostRequest extends FormRequest
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
            'tipo_actividad_id'=> ['required','integer','exists:tipos_actividad,id'],
            'capacitador_id' => ['nullable','integer','exists:capacitadores,id'],
            'beneficiario_id'  => ['required','integer','exists:beneficiarios,id'],
            'nombre' => [
                'required',
                'string',
                Rule::unique('actividades','nombre')
                ->where(function ($query) {
                    return $query
                        ->where('tipo_actividad_id', $this->tipo_actividad_id)
                        ->where('proyecto_id', $this->route('proyecto')->id);
                })
            ],
            'responsable_id'=> ['required','integer','exists:personas,id'],
            'fecha_inicio'  => ['required','date'],
            'fecha_fin'  => ['required','date'],
            'persona_beneficiada' => ['required','array'],
            'prioridad' => ['required','string'],
            'autoridad_participante' => ['nullable','array'],
            'link_drive' => ['nullable','string', 'url'],
            'fecha_solicitud_constancia' => ['nullable','date'],
            'fecha_envio_constancia' => ['nullable','date'],
            'fecha_vencimiento_envio_encuesta' => ['nullable','date'],
            'fecha_copy_creativo' => ['nullable','date'],
            'fecha_inicio_difusion_banner' => ['nullable','date'],
            'fecha_fin_difusion_banner' => ['nullable','date'],
            'link_registro' => ['nullable','string', 'url'],
            'registro_nafin' => ['nullable','string'],
            'link_zoom' => ['nullable','string', 'url'],
            'link_panelista' => ['nullable','string', 'url'],
            'comentario' => ['nullable','string'],
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
            'tipo_actividad_id.required' => 'El tipo de actividad es requerido.',
            'tipo_actividad_id.integer' => 'El tipo de actividad debe ser un número entero.',
            'tipo_actividad_id.exists' => 'El tipo de actividad seleccionado no es válido.',
            'capacitador_id.integer' => 'El capacitador debe ser un número entero.',
            'capacitador_id.exists' => 'El capacitador seleccionado no es válido.',
            'beneficiario_id.required' => 'El beneficiario es requerido.',
            'beneficiario_id.integer' => 'El beneficiario debe ser un número entero.',
            'beneficiario_id.exists' => 'El beneficiario seleccionado no es válido.',
            'nombre.required' => 'El nombre de la actividad es requerido.',
            'nombre.string' => 'El nombre de la actividad debe ser una cadena de texto.',
            'nombre.unique' => 'Ya existe una actividad con este nombre para el tipo de actividad y proyecto seleccionados.',
            'responsable_id.required' => 'El responsable es requerido.',
            'responsable_id.integer' => 'El responsable debe ser un número entero.',
            'responsable_id.exists' => 'El responsable seleccionado no es válido.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin es requerida.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'persona_beneficiada.required' => 'La persona beneficiada es requerida.',
            'persona_beneficiada.array' => 'La persona beneficiada debe ser un arreglo.',
            'prioridad.required' => 'La prioridad es requerida.',
            'prioridad.string' => 'La prioridad debe ser una cadena de texto.',
            'autoridad_participante.array' => 'La autoridad participante debe ser un arreglo.',
            'link_drive.string' => 'El link de Drive debe ser una cadena de texto.',
            'fecha_solicitud_constancia.date' => 'La fecha de solicitud de constancia debe ser una fecha válida.',
            'fecha_envio_constancia.date' => 'La fecha de envío de constancia debe ser una fecha válida.',
            'fecha_vencimiento_envio_encuesta.date' => 'La fecha de vencimiento de envío de encuesta debe ser una fecha válida.',
            'fecha_copy_creativo.date' => 'La fecha de copy creativo debe ser una fecha válida.',
            'fecha_inicio_difusion_banner.date' => 'La fecha de inicio de difusión de banner debe ser una fecha válida.',
            'fecha_fin_difusion_banner.date' => 'La fecha de fin de difusión de banner debe ser una fecha válida.',
            'link_registro.string' => 'El link de registro debe ser una cadena de texto.',
            'registro_nafin.string' => 'El registro NAFIN debe ser una cadena de texto.',
            'link_zoom.string' => 'El link de Zoom debe ser una cadena de texto.',
            'link_panelista.string' => 'El link de panelista debe ser una cadena de texto.',
            'link_drive.url' => 'El link de Drive debe ser una URL válida.',
            'link_registro.url' => 'El link de registro debe ser una URL válida.',
            'link_zoom.url' => 'El link de Zoom debe ser una URL válida.',
            'link_panelista.url' => 'El link de panelista debe ser una URL válida.',
            'comentario.string' => 'El comentario debe ser una cadena de texto.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [];
    }
}
