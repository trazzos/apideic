<?php

namespace App\Http\Resources\Actividad;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActividadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'uuid' => $this->uuid,
            'proyecto_uuid' => $this->proyecto_uuid,
            'tipo_actividad_id' => $this->tipo_actividad_id,
            'tipo_actividad_nombre' => $this->tipoActividad?->nombre,
            'capacitador_id' => $this->capacitador_id,
            'capacitador_nombre' => $this->capacitador?->nombre,
            'beneficiario_id' => $this->beneficiario_id,
            'beneficiario_nombre' => $this->beneficiario?->nombre,
            'responsable_id' => $this->responsable_id,
            'responsable_nombre' => $this->responsable?->nombre,
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'persona_beneficiada' => $this->persona_beneficiada,
            'prioridad' => $this->prioridad,
            'autoridad_participante' => $this->autoridad_participante,
            'link_drive' => $this->link_drive,
            'fecha_solicitud_constancia' => $this->fecha_solicitud_constancia,
            'fecha_envio_constancia' => $this->fecha_envio_constancia,
            'fecha_vencimiento_envio_encuesta' => $this->fecha_vencimiento_envio_encuesta,
            'fecha_envio_encuesta' => $this->fecha_envio_encuesta,
            'fecha_copy_creativo' => $this->fecha_copy_creativo,
            'fecha_inicio_difusion_banner' => $this->fecha_inicio_difusion_banner,
            'fecha_fin_difusion_banner' => $this->fecha_fin_difusion_banner,
            'link_registro' => $this->link_registro,
            'registro_nafin' => $this->registro_nafin,
            'link_zoom' => $this->link_zoom,
            'link_penalista' => $this->link_penalista,
            'comentario' => $this->comentario,

        ];
    }
}
