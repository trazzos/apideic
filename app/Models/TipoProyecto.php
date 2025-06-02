<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoProyecto extends Model
{
    use SoftDeletes;

    protected $table = 'tipos_proyecto';

    protected $guarded = [];
}
