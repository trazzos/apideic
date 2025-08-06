<?php

namespace App\Services;

use App\Http\Resources\Proyecto\ProyectoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\ProyectoRepository;
use App\Dtos\Proyecto\CreateProyectoDto;
use App\Dtos\Proyecto\UpdateProyectoDto;
use App\Services\Traits\Searchable;

class ProyectoService extends BaseService {

    use Searchable;
    /**
     * @param ProyectoRepository $proyectoRepository
     */
    public function __construct(private readonly ProyectoRepository $proyectoRepository)
    {
        $this->repository = $this->proyectoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Proyecto\\ProyectoCollection";
        $this->customResource = "App\\Http\\Resources\\Proyecto\\ProyectoResource";
    }


    /**
    * @param CreateProyectoDto $createProyectoDto
    * @return JsonResource
    */
    public function createFromDto(CreateProyectoDto $createProyectoDto): JsonResource
    {

        $data = [
            'tipo_proyecto_id' => $createProyectoDto->tipoProyectoId,
            'departamento_id' => $createProyectoDto->departamentoId,
            'uuid' => $createProyectoDto->uuid,
            'nombre' => $createProyectoDto->nombre,
            'descripcion' => $createProyectoDto->descripcion,
        ];

        $jsonResource =  parent::create($data);
        return ProyectoResource::make($jsonResource);
    }


    /**
     * @param UpdateProyectoDto $updateProyectoDto
     * @param int $id
     * @return JsonResource
     */
    public function updateFromDto(UpdateProyectoDto $updateProyectoDto, $id): JsonResource
    {

        $data = [
            'tipo_proyecto_id' => $updateProyectoDto->tipoProyectoId,
            'departamento_id' => $updateProyectoDto->departamentoId,
            'nombre' => $updateProyectoDto->nombre,
            'descripcion' => $updateProyectoDto->descripcion,
        ];

        $jsonResource = parent::update($id, $data);

        return ProyectoResource::make($jsonResource);
    }
}
