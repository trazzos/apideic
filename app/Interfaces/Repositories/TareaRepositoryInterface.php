<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface TareaRepositoryInterface extends BaseRepositoryInterface{

    /**
     * Find tasks by the UUID of the associated activity.
     *
     * @param string $uuid
     * @return mixed
     */
    public function findByActividadUuid(string $uuid):Collection;
}
