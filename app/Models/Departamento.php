<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use SoftDeletes;

    protected $table = 'departamentos';

    protected $guarded = [];

    public function proyectos(): HasMany {

        return $this->hasMany(Proyecto::class);
    }

    public function personas(): HasMany {

        return $this->hasMany(Persona::class);
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class);
    }
}
