<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

abstract class BaseService
{
    /**
     * @var string
     */
    protected $customResourceCollection = "";
    /**
     * @var string
     */
    protected $customResource = "";

   /**
    * @var
    */
    protected $repository;

    /**
     * @return ResourceCollection
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null): ResourceCollection
    {
        $rows = $this->repository->paginate($perPage, $columns, $pageName, $page);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
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
    public function create(array $data): JsonResource
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
    public function update(int $id, array $data):JsonResource
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
    public function delete($id):Response
    {
        $deleted = $this->repository->delete($id);

        if ($deleted) {
            return response()->noContent();
        } else {
        throw new ModelNotFoundException("No se encontró registro con ID {$id} para eliminar.");
        }
    }

}
