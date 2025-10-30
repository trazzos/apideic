<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface UnidadApoyoRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySecretaria(int $secretariaId): Collection;
}
