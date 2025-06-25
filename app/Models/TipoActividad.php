<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoActividad extends Model
{
    use SoftDeletes;

    protected $table = 'tipos_actividad';

    protected $guarded = [];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'tipo_actividad_id');
    }
}
