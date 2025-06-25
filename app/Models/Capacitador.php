<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capacitador extends Model
{
    use SoftDeletes;

    protected $table = 'capacitadores';

    protected $guarded = [];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'capacitador_id');
    }
}
