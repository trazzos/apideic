<?php

namespace App\Services;

use App\Dtos\Proyecto\CreateActividadDto;
use App\Http\Resources\Actividad\ActividadResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Interfaces\Repositories\ActividadRepositoryInterface;
use App\Dtos\Proyecto\UpdateActividadDto;
use Illuminate\Http\Resources\Json\ResourceCollection;


class ActividadService extends BaseService {

    /**
     * @param ActividadRepositoryInterface $actividadRepository
     */
    
    public function __construct(ActividadRepositoryInterface $actividadRepository)
    {
        $this->repository = $actividadRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Actividad\\ActividadCollection";
        $this->customResource = "App\\Http\\Resources\\Actividad\\ActividadResource";
    }

    public function listByProyectoUuid(string $uuid):ResourceCollection
    {
        $rows = $this->repository->findByProyectoUuid($uuid);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }
        return ($this->customResourceCollection)::make($rows);
    }


    /**
    * @param CreateActividadDto $createActividadDto
    * @return JsonResource
    */
    public function createFromDto(CreateActividadDto $createActividadDto): JsonResource
    {

        $data = [
            'proyecto_id' => $createActividadDto->proyectoId,
            'uuid' => $createActividadDto->uuid,
            'proyecto_uuid' => $createActividadDto->uuidProyecto,
            'tipo_actividad_id' => $createActividadDto->tipoActividadId,
            'capacitador_id' => $createActividadDto->capacitadorId,
            'beneficiario_id' => $createActividadDto->beneficiarioId,
            'nombre' => $createActividadDto->nombre,
            'responsable_id'=> $createActividadDto->responsableId,
            'fecha_inicio'  => $createActividadDto->fechaInicio->toDateString(),
            'fecha_final'  => $createActividadDto->fechaFin->toDateString(),
            'persona_beneficiada' => $createActividadDto->personaBeneficiada,
            'prioridad' => $createActividadDto->prioridad,
            'autoridad_participante' => $createActividadDto->autoridadParticipante,
            'link_drive' => $createActividadDto->linkDrive ?? '',
            'fecha_solicitud_constancia' => $createActividadDto->fechaSolicitudConstancia?->toDateString(),
            'fecha_envio_constancia' => $createActividadDto->fechaEnvioConstancia?->toDateString(),
            'fecha_vencimiento_envio_encuesta' => $createActividadDto->fechaVencimientoEnvioEncuesta?->toDateString(),
            'fecha_envio_encuesta' => $createActividadDto->fechaEnvioEncuesta?->toDateString(),
            'fecha_copy_creativo' => $createActividadDto->fechaCopyCreativo?->toDateString(),
            'fecha_inicio_difusion_banner' => $createActividadDto->fechaInicioDifusionBanner?->toDateString(),
            'fecha_fin_difusion_banner' => $createActividadDto->fechaFinDifusionBanner?->toDateString(),
            'link_registro' => $createActividadDto->linkRegistro,
            'registro_nafin' => $createActividadDto->registroNafin,
            'link_zoom' => $createActividadDto->linkZoom,
            'link_panelista' => $createActividadDto->linkPanelista,
            'comentario' => $createActividadDto->comentario,
        ];



        $jsonResource =  parent::create($data);
        return ActividadResource::make($jsonResource);
    }


    /**
     * @param UpdateActividadDto $updateProyectoDto
     * @param int $id
     * @return JsonResource
     */
    public function updateFromDto(UpdateActividadDto $updateActividadDto, $id): JsonResource
    {

        $data = [
            'tipo_actividad_id' => $updateActividadDto->tipoActividadId,
            'capacitador_id' => $updateActividadDto->capacitadorId,
            'beneficiario_id' => $updateActividadDto->beneficiarioId,
            'nombre' => $updateActividadDto->nombre,
            'responsable_id'=> $updateActividadDto->responsableId,
            'fecha_inicio'  => $updateActividadDto->fechaInicio->toDateString(),
            'fecha_final'  => $updateActividadDto->fechaFin->toDateString(),
            'persona_beneficiada' => $updateActividadDto->personaBeneficiada,
            'prioridad' => $updateActividadDto->prioridad,
            'autoridad_participante' => $updateActividadDto->autoridad,
            'link_drive' => $updateActividadDto->linkDrive ?? '',
            'fecha_solicitud_constancia' => $updateActividadDto->fechaSolicitudConstancia?->toDateString(),
            'fecha_envio_constancia' => $updateActividadDto->fechaEnvioConstancia?->toDateString(),
            'fecha_vencimiento_envio_encuesta' => $updateActividadDto->fechaVencimientoEnvioEncuesta?->toDateString(),
            'fecha_envio_encuesta' => $updateActividadDto->fechaEnvioEncuesta?->toDateString(),
            'fecha_copy_creativo' => $updateActividadDto->fechaCopyCreativo?->toDateString(),
            'fecha_inicio_difusion_banner' => $updateActividadDto->fechaInicioDifusionBanner?->toDateString(),
            'fecha_fin_difusion_banner' => $updateActividadDto->fechaFinDifusionBanner?->toDateString(),
            'link_registro' => $updateActividadDto->ligaRegistro,
            'registro_nafin' => $updateActividadDto->registroNafin,
            'link_zoom' => $updateActividadDto->ligaZoom,
            'link_panelista' => $updateActividadDto->ligaPanelista,
            'comentario' => $updateActividadDto->comentario,
        ];

        $jsonResource = parent::update($id, $data);

        return ActividadResource::make($jsonResource);
    }

    /**
     * Obtener el progreso de una actividad específica
     */
    public function getProgress(int $id): array
    {
        $actividad = $this->repository->findById($id);
        
        if (!$actividad) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Actividad con ID {$id} no encontrada.");
        }

        return $actividad->getProgress();
    }
}
