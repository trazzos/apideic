<?php

namespace App\Http\Resources\Catalogo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Colección de recursos para catálogos consolidados.
 * 
 * Formatea la respuesta JSON de todos los catálogos del sistema
 * en una estructura consistente y optimizada.
 */
class CatalogoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'departamentos' => $this->formatCatalogo($this->resource['departamentos']),
            'tipos_documento' => $this->formatCatalogo($this->resource['tipos_documento']),
            'tipos_proyecto' => $this->formatCatalogo($this->resource['tipos_proyecto']),
            'tipos_actividad' => $this->formatCatalogo($this->resource['tipos_actividad']),
            'beneficiarios' => $this->formatCatalogoBeneficiarios($this->resource['beneficiarios']),
            'autoridades' => $this->formatCatalogoAutoridades($this->resource['autoridades']),
            'responsables' => $this->formatCatalogoResponsables($this->resource['responsables']),
            'capacitadores' => $this->formatCatalogoCapacitadores($this->resource['capacitadores']),
        ];
    }

    /**
     * Formatear catálogo básico (id, nombre, descripción).
     *
     * @param mixed $items
     * @return array
     */
    private function formatCatalogo($items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'descripcion' => $item->descripcion ?? null,
            ];
        })->toArray();
    }

    /**
     * Formatear catálogo de beneficiarios.
     *
     * @param mixed $items
     * @return array
     */
    private function formatCatalogoBeneficiarios($items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'contacto' => $item->contacto ?? null,
                'email' => $item->email ?? null,
            ];
        })->toArray();
    }

    /**
     * Formatear catálogo de autoridades.
     *
     * @param mixed $items
     * @return array
     */
    private function formatCatalogoAutoridades($items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'cargo' => $item->cargo ?? null,
                'institucion' => $item->institucion ?? null,
            ];
        })->toArray();
    }

    /**
     * Formatear catálogo de responsables.
     *
     * @param mixed $items
     * @return array
     */
    private function formatCatalogoResponsables($items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'apellido' => $item->apellido ?? null,
                'nombre_completo' => trim($item->nombre . ' ' . ($item->apellido ?? '')),
                'email' => $item->email ?? null,
                'telefono' => $item->telefono ?? null,
            ];
        })->toArray();
    }

    /**
     * Formatear catálogo de capacitadores.
     *
     * @param mixed $items
     * @return array
     */
    private function formatCatalogoCapacitadores($items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'especialidad' => $item->especialidad ?? null,
                'contacto' => $item->contacto ?? null,
            ];
        })->toArray();
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'generated_at' => now()->toISOString(),
                'total_catalogos' => 8,
            ],
        ];
    }
}
