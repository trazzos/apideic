<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TipoActividad extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'tipos_actividad';

    protected $guarded = [];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'tipo_actividad_id');
    }
}
