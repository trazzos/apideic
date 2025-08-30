<?php

namespace App\Services;

use App\Models\Actividad;
use App\Models\Autoridad;
use App\Models\Beneficiario;
use App\Models\Capacitador;
use App\Models\Departamento;
use App\Models\Persona;
use App\Models\TipoActividad;
use App\Models\TipoDocumento;
use App\Models\TipoProyecto;

/**
 * Servicio para la gestión consolidada de catálogos del sistema.
 * 
 * Este servicio proporciona métodos para obtener todos los catálogos
 * del sistema de manera optimizada, reduciendo la cantidad de peticiones
 * necesarias desde el frontend.
 */
class CatalogoService extends BaseService
{
    /**
     * Obtener todos los catálogos del sistema.
     *
     * @return array Arreglo asociativo con todos los catálogos
     * @throws \Exception Si hay errores al obtener los datos
     */
    public function getAllCatalogos(): array
    {
        try {
            return [
                'departamentos' => $this->getDepartamentos(),
                'tipos_documento' => $this->getTiposDocumento(),
                'tipos_proyecto' => $this->getTiposProyecto(),
                'tipos_actividad' => $this->getTiposActividad(),
                'beneficiarios' => $this->getBeneficiarios(),
                'autoridades' => $this->getAutoridades(),
                'responsables' => $this->getResponsables(),
                'capacitadores' => $this->getCapacitadores(),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener los catálogos: ' . $e->getMessage());
        }
    }

    /**
     * Obtener departamentos activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDepartamentos()
    {
        return Departamento::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener tipos de documento activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTiposDocumento()
    {
        return TipoDocumento::select('id', 'nombre','descripcion')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener tipos de proyecto activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTiposProyecto()
    {
        return TipoProyecto::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener tipos de actividad activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTiposActividad()
    {
        return TipoActividad::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener beneficiarios activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getBeneficiarios()
    {
        return Beneficiario::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener autoridades activas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAutoridades()
    {
        return Autoridad::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener responsables (personas) activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getResponsables()
    {
        $personas = Persona::select(
            'id',
            'nombre',
            'apellido_paterno',
            'apellido_materno', 
            'es_titular')
            ->orderBy('nombre')
            ->get();

            return $personas->map(function ($persona) {
                return collect([
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'apellido_paterno' => $persona->apellido_paterno,
                    'apellido_materno' => $persona->apellido_materno,
                    'es_titular' => $persona->es_titular,
                    'tipo_dependencia' => $persona->tipo_dependencia,
                    'dependencia_id' => $persona->dependencia->id ?? null,
                    'dependencia' => $persona->dependencia->nombre ?? null
                ]);
            });
    }

    /**
     * Obtener capacitadores activos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getCapacitadores()
    {
        return Capacitador::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();
    }
}
