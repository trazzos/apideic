<?php

namespace App\Dtos\Proyecto;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CreateActividadDto
{

    /**
     * @param int $proyectoId
     * @param string $uuidProyecto
     * @param string $uuid
     * @param int $tipoActividadId
     * @param int $capacitadorId
     * @param int $beneficiarioId
     * @param string $nombre
     * @param int $responsableId
     * @param Carbon $fechaInicio
     * @param Carbon $fechaFin
     * @param string $personaBeneficiada
     * @param string $prioridad
     * @param array|null $autoridadParticipante
     * @param string|null $linkDrive
     * @param Carbon|null $fechaSolicitudConstancia
     * @param Carbon|null $fechaEnvioConstancia
     * @param Carbon|null $fechaVencimientoEnvioEncuesta
     * @param Carbon|null $fechaEnvioEncuesta
     * @param Carbon|null $fechaCopyCreativo
     * @param Carbon|null $fechaInicioDifusionBanner
     * @param Carbon|null $fechaFinDifusionBanner
     * @param string|null $linkRegistro
     * @param string|null $registroNafin
     * @param string|null $linkZoom
     * @param string|null $linkPanelista
     * @param string|null $comentario
     */
    public function __construct(
        public readonly int $proyectoId,
        public readonly string $uuidProyecto,
        public readonly string $uuid,
        public readonly int $tipoActividadId,
        public readonly int $beneficiarioId,
        public readonly string $nombre,
        public readonly int $responsableId,
        public readonly Carbon $fechaInicio,
        public readonly Carbon $fechaFin,
        public readonly array $personaBeneficiada,
        public readonly string $prioridad,
        public readonly ?int $capacitadorId,
        public readonly ?array $autoridadParticipante,
        public readonly ?string $linkDrive,
        public readonly ?Carbon $fechaSolicitudConstancia,
        public readonly ?Carbon $fechaEnvioConstancia,
        public readonly ?Carbon $fechaVencimientoEnvioEncuesta,
        public readonly ?Carbon $fechaEnvioEncuesta,
        public readonly ?Carbon $fechaCopyCreativo,
        public readonly ?Carbon $fechaInicioDifusionBanner,
        public readonly ?Carbon $fechaFinDifusionBanner,
        public readonly ?string $linkRegistro,
        public readonly ?string $registroNafin,
        public readonly ?string $linkZoom,
        public readonly ?string $linkPanelista,
        public readonly ?string $comentario,
    )
    {

    }

    /*
     * @param Request $request
     * @return CreateProyectoDto
     */
    public static function fromRequest(int $proyectoId, string $uuidProyecto, Request $request): self
    {
        return new self(
            $proyectoId,
            $uuidProyecto,
            Str::uuid(),
            $request->input('tipo_actividad_id'),
            $request->input('beneficiario_id'),
            $request->input('nombre'),
            $request->input('responsable_id'),
            Carbon::parse($request->input('fecha_inicio')),
            Carbon::parse($request->input('fecha_fin')),
            $request->input('persona_beneficiada'),
            $request->input('prioridad'),
            $request->input('capacitador_id'),
            $request->input('autoridad_participante'),
            $request->input('link_drive'),
            $request->input('fecha_solicitud_constancia') ? Carbon::parse($request->input('fecha_solicitud_constancia')) : null,
            $request->input('fecha_envio_constancia') ? Carbon::parse($request->input('fecha_envio_constancia')) : null,
            $request->input('fecha_vencimiento_envio_encuesta') ? Carbon::parse($request->input('fecha_vencimiento_envio_encuesta')) : null,
            $request->input('fecha_envio_encuesta') ? Carbon::parse($request->input('fecha_envio_encuesta')) : null,
            $request->input('fecha_copy_creativo') ? Carbon::parse($request->input('fecha_copy_creativo')) : null,
            $request->input('fecha_inicio_difusion_banner') ? Carbon::parse($request->input('fecha_inicio_difusion_banner')) : null,
            $request->input('fecha_fin_difusion_banner') ? Carbon::parse($request->input('fecha_fin_difusion_banner')) : null,
            $request->input('link_registro') ?? '',
            $request->input('registro_nafin') ?? '',
            $request->input('link_zoom') ?? '',
            $request->input('link_panelista') ?? '',
            $request->input('comentario') ?? '',
        );
    }
}
