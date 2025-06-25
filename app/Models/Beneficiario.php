<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiario extends Model
{
    use SoftDeletes;

    protected $table = 'beneficiarios';

    protected $guarded = [];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'beneficiario_id');
    }
}
