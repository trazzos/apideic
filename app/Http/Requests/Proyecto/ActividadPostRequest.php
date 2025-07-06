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
            'capacitador_id' => ['required','integer','exists:capacitadores,id'],
            'beneficiario_id'  => ['required','integer','exists:beneficiarios,id'],
            'nombre' => ['required','string',Rule::unique('actividades','nombre')->whereNot('tipo_actividad_id',$this->tipo_actividad_id)],
            'responsable_id'=> ['required','integer','exists:personas,id'],
            'fecha_inicio'  => ['required','date'],
            'fecha_fin'  => ['required','date'],
            'persona_beneficiada' => ['required','string'],
            'prioridad' => ['required','string'],
            'autoridad_participante' => ['nullable','array'],
            'link_drive' => ['nullable','string'],
            'fecha_solicitud_constancia' => ['nullable','date'],
            'fecha_envio_constancia' => ['nullable','date'],
            'fecha_vencimiento_envio_encuesta' => ['nullable','date'],
            'fecha_copy_creativo' => ['nullable','date'],
            'fecha_inicio_difusion_banner' => ['nullable','date'],
            'fecha_fin_difusion_banner' => ['nullable','date'],
            'link_registro' => ['nullable','string'],
            'registro_nafin' => ['nullable','string'],
            'link_zoom' => ['nullable','string'],
            'link_panelista' => ['nullable','string'],
            'comentario' => ['nullable','string'],
        ];
    }
}
