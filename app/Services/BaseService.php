<?php

namespace App\Services;

use App\Repositories\Eloquent\BaseEloquentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

abstract class BaseService
{
    /**
     *
     */
    protected $customResourceCollection = "";
    protected $customResource = "";
    /**
     * @var BaseEloquentRepository $repository
     */
    protected BaseEloquentRepository $repository;

    /**
     * @return ResourceCollection
     */
    public function lista(): ResourceCollection
    {
        $rows = $this->repository->all();

        if ($this->customResourceCollection)
            return new $this->customResourceCollection($rows);

        return ResourceCollection::make($rows);
    }

    /**
     * @param int $id
     * @return JsonResource
     */
    public function findById($id): JsonResource
    {
        $row = $this->repository->findById($id);

        if ($this->customResource)
            return new $this->customResource($row);

        return JsonResource::make($row);
    }

    /**
     * @param array $data
     * @return JsonResource
     */
    public function crear(array $data): JsonResource
    {
        $nuevo = $this->repository->create($data);

        if ($this->customResource)
            return new $this->customResource($nuevo);

        return JsonResource::make($nuevo);
    }

    /**
     * @param int $id
     * @param array $data
     * @return JsonResource
     * @throws ModelNotFoundException
     */
    public function actualizar(int $id, array $data):JsonResource
    {
        $nuevo = $this->repository->updateAndReturn($id, $data);
        if (!$nuevo) {
            throw new ModelNotFoundException("Registro con ID {$id} no encontrado para actualizar.");
        }
        if ($this->customResource)
            return new $this->customResource($nuevo);

        return JsonResource::make($nuevo);
    }

    /**
     * @param int $id
     * @return Response
     * @throws ModelNotFoundException
     */
    public function eliminar($id):Response
    {
        $deleted = $this->repository->delete($id);

        if ($deleted) {
            return response()->noContent();
        } else {
           throw new ModelNotFoundException("No se encontró registro con ID {$id} para eliminar.");
        }
    }

}
