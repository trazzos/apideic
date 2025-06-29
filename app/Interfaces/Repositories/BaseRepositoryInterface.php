<?php

namespace App\Interfaces\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function create(array $data):mixed;

    public function findById(mixed $id): Model | ModelNotFoundException;

    public function updateAndReturn(mixed $id, array $data): ?Model;

    public function delete(mixed $id): bool;

    public function all(array $columns = ['*'], array $relations = []): \Illuminate\Support\Collection;

    /**
     * Paginate the model's records.
     *
     * @param int $perPage
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
}
