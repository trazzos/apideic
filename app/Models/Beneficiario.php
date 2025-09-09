<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Beneficiario extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'beneficiarios';

    protected $guarded = [];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'beneficiario_id');
    }
}
