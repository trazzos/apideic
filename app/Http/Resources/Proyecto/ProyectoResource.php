<?php

namespace App\Http\Resources\Proyecto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "tipo_proyecto_id" => $this->tipo_proyecto_id,
            "tipo_proyecto_nombre" => $this->tipoProyecto->nombre,
            "departamento_id" => $this->departamento_id,
            "departamento_nombre" => $this->departamento->nombre,
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
            "porcentaje_avance" => $this->getProgress()['porcentaje_completado'],
            "total_tareas" => $this->getProgress()['total_tareas'],
            "tareas_completadas" => $this->getProgress()['tareas_completadas'],
            "tareas_pendientes" => $this->getProgress()['tareas_pendientes'],
            "estatus" => $this->isCompleted() ? 'Completado' : 'Pendiente', // Atributo calculado

        ];
    }
}
