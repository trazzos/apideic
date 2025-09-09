<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable; 

class TipoProyecto extends Model implements Auditable
{
    use SoftDeletes,\OwenIt\Auditing\Auditable;

    protected $table = 'tipos_proyecto';

    protected $guarded = [];


    public function proyectos(): HasMany {

        return $this->hasMany(Proyecto::class);
    }
}
