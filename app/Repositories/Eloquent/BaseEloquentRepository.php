<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

abstract class BaseEloquentRepository implements BaseRepositoryInterface
{
    /**
     * @var Model $model
     */
    protected Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model =  $model;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
    * @param mixed $id
    * @return Model
    * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
    */
    public function findById(mixed $id): Model | ModelNotFoundException
    {
        return $this->model->findOrFail($id);
    }

    /**
    * @param mixed $id
    * @param array $data
    * @return Model
    */
    public function updateAndReturn(mixed $id, array $data): ?Model
    {
        $model = $this->model->where('id', $id)->first();
        if ($model) {
            $model->update($data);
            return $model;
        }

        return null;
    }

    /**
     * @param mixed $id
     * @param bool
     */
    public function delete(mixed $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    /**
     * @param array $columns
     * @param array $relations
     * @param Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }
}
