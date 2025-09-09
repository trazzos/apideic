<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Capacitador extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'capacitadores';

    protected $guarded = [];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'capacitador_id');
    }
}
