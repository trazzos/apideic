<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\ProyectoRepository;
use App\Dtos\Proyecto\CreateProyectoDto;
use App\Dtos\Proyecto\UpdateProyectoDto;


class ProyectoService extends BaseService {

    /**
     * @param ProyectoRepository $proyectoRepository
     */
    public function __construct(private readonly ProyectoRepository $proyectoRepository)
    {
        $this->repository = $this->proyectoRepository;
    }


    /**
    * @param CreateProyectoDto $createProyectoDto
    * @return JsonResource
    */
    public function crearDesdeDto(CreateProyectoDto $createProyectoDto): JsonResource
    {

        $data = [
            'tipo_proyecto_id' => $createProyectoDto->tipoProyectoId,
            'departamento_id' => $createProyectoDto->departamentoId,
            'uuid' => $createProyectoDto->uuid,
            'nombre' => $createProyectoDto->nombre,
            'descripcion' => $createProyectoDto->descripcion,
        ];

        return parent::crear($data);
    }


    /**
     * @param UpdateProyectoDto $updateProyectoDto
     * @param int $id
     * @return JsonResource
     */
    public function actualizarDesdeDto(UpdateProyectoDto $updateProyectoDto, $id): JsonResource
    {

        $data = [
            'tipo_proyecto_id' => $updateProyectoDto->tipoProyectoId,
            'departamento_id' => $updateProyectoDto->departamentoId,
            'nombre' => $updateProyectoDto->nombre,
            'descripcion' => $updateProyectoDto->descripcion,
        ];

        return parent::actualizar($id, $data);
    }
}
