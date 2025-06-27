<?php

namespace App\Services;

use App\Dtos\Proyecto\CreateActividadDto;
use App\Http\Resources\Actividad\ActividadResource;
use App\Http\Resources\Proyecto\ProyectoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\ActividadRepository;
use App\Dtos\Proyecto\UpdateActividadDto;
use Illuminate\Http\Resources\Json\ResourceCollection;


class ActividadService extends BaseService {

    /**
     * @param ActividadRepository $actividadRepository
     */
    public function __construct(private readonly ActividadRepository $actividadRepository)
    {
        $this->repository = $this->actividadRepository;
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
            'link_drive' => $createActividadDto->linkDrive,
            'fecha_solicitud_constancia' => $createActividadDto->fechaSolicitudConstancia,
            'fecha_envio_constancia' => $createActividadDto->fechaEnvioConstancia,
            'fecha_vencimiento_envio_encuesta' => $createActividadDto->fechaVencimientoEnvioEncuesta,
            'fecha_envio_encuesta' => $createActividadDto->fechaEnvioEncuesta,
            'fecha_copy_creativo' => $createActividadDto->fechaCopyCreativo,
            'fecha_inicio_difusion_banner' => $createActividadDto->fechaInicioDifusionBanner,
            'fecha_fin_difusion_banner' => $createActividadDto->fechaFinDifusionBanner,
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
            'autoridad' => $updateActividadDto->autoridad,
            'link_drive' => $updateActividadDto->linkDrive,
            'fecha_solicitud_constancia' => $updateActividadDto->fechaSolicitudConstancia,
            'fecha_envio_constancia' => $updateActividadDto->fechaEnvioConstancia,
            'fecha_vencimiento_envio_encuesta' => $updateActividadDto->fechaVencimientoEnvioEncuesta,
            'fecha_envio_encuesta' => $updateActividadDto->fechaEnvioEncuesta,
            'fecha_copy_creativo' => $updateActividadDto->fechaCopyCreativo,
            'fecha_inicio_difusion_banner' => $updateActividadDto->fechaInicioDifusionBanner,
            'fecha_fin_difusion_banner' => $updateActividadDto->fechaFinDifusionBanner,
            'liga_registro' => $updateActividadDto->ligaRegistro,
            'registro_nafin' => $updateActividadDto->registroNafin,
            'liga_zoom' => $updateActividadDto->ligaZoom,
            'liga_panelista' => $updateActividadDto->ligaPanelista,
            'comentario' => $updateActividadDto->comentario,
        ];

        $jsonResource = parent::update($id, $data);

        return ProyectoResource::make($jsonResource);
    }
}
