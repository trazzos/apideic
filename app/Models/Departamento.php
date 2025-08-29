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

    /**
     * Personas asignadas a este departamento mediante relación polimórfica.
     */
    public function personas(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Persona::class, 'dependencia');
    }

    /**
     * Dirección a la que pertenece este departamento.
     */
    public function direccion()
    {
        return $this->belongsTo(Direccion::class);
    }

    /**
     * Obtener la dependencia padre (Direccion).
     */
    public function getPadre()
    {
        return $this->direccion;
    }

    /**
     * Obtener la jerarquía completa hasta la Secretaría.
     */
    public function getJerarquiaCompleta(): array
    {
        $jerarquia = [$this];
        
        if ($direccion = $this->direccion) {
            $jerarquia = array_merge($direccion->getJerarquiaCompleta(), $jerarquia);
        }
        
        return $jerarquia;
    }
}
