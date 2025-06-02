<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\PersonaRepositoryInterface;
use App\Models\Persona;

class PersonaRepository extends BaseEloquentRepository implements PersonaRepositoryInterface
{
    public function __construct(Persona $model)
    {
        parent::__construct($model);
    }
}
