<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Subsecretaria extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'subsecretarias';

    protected $guarded = [];

    /**
     * Secretaria a la que pertenece esta subsecretaria.
     */
    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class);
    }

    /**
     * Direcciones que pertenecen a esta subsecretaria.
     */
    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }

    /**
     * Personas asignadas directamente a esta subsecretaria mediante relación polimórfica.
     */
    public function personas(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Persona::class, 'dependencia');
    }

    /**
     * Obtener la dependencia padre (Secretaria).
     */
    public function getPadre()
    {
        return $this->secretaria;
    }

    /**
     * Obtener la jerarquía completa hasta la Secretaría.
     */
    public function getJerarquiaCompleta(): array
    {
        $jerarquia = [$this];
        
        if ($secretaria = $this->secretaria) {
            $jerarquia = array_merge([$secretaria], $jerarquia);
        }
        
        return $jerarquia;
    }
}
