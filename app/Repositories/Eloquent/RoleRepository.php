<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\UserRepositoryInterface;
use Spatie\Permission\Models\Role;


class RoleRepository extends BaseEloquentRepository implements UserRepositoryInterface
{

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
