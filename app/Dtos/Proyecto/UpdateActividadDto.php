<?php

namespace App\Dtos\Proyecto;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UpdateActividadDto
{

    /*
     * @param int $tipoProyectoId
     * @param int $departamentoId
     * @param string $nombre
     * @param string $descripcion
     */
    public function __construct(
        public readonly int $tipoActividadId,
        public readonly int $capacitadorId,
        public readonly int $beneficiarioId,
        public readonly string $nombre,
        public readonly int $responsableId,
        public readonly Carbon $fechaInicio,
        public readonly Carbon $fechaFin,
        public readonly string $personaBeneficiada,
        public readonly string $prioridad,
        public readonly ?array $autoridad,
        public readonly ?string $linkDrive,
        public readonly ?Carbon $fechaSolicitudConstancia,
        public readonly ?Carbon $fechaEnvioConstancia,
        public readonly ?Carbon $fechaVencimientoEnvioEncuesta,
        public readonly ?Carbon $fechaEnvioEncuesta,
        public readonly ?Carbon $fechaCopyCreativo,
        public readonly ?Carbon $fechaInicioDifusionBanner,
        public readonly ?Carbon $fechaFinDifusionBanner,
        public readonly ?string $ligaRegistro,
        public readonly ?string $registroNafin,
        public readonly ?string $ligaZoom,
        public readonly ?string $ligaPanelista,
        public readonly ?string $comentario,
    )
    {

    }

    /*
     * @param Request $request
     * @return UpdateProyectoDto
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('tipo_actividad_id'),
            $request->input('capacitador_id'),
            $request->input('beneficiario_id'),
            $request->input('nombre'),
            $request->input('responsable_id'),
            Carbon::parse($request->input('fecha_inicio')),
            Carbon::parse($request->input('fecha_fin')),
            $request->input('persona_beneficiada'),
            $request->input('prioridad'),
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
