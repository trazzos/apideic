<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
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
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param int $perPage
     * @param array $with
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function paginateWith(int $perPage = 15, array $with = [], array $columns = ['*'], string $pageName = 'page', int $page = null): LengthAwarePaginator
    {
        return $this->model->with($with)->paginate($perPage, $columns, $pageName, $page);
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
     * Buscar archivo por UUID.
     * 
     * @param string $uuid UUID del archivo
     * @return Archivo
     */
    public function findByUuid(string $uuid): Archivo
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
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
