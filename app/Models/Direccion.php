<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Direccion extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'direcciones';

    protected $guarded = [];

    /**
     * Subsecretaria a la que pertenece esta dirección.
     */
    public function subsecretaria()
    {
        return $this->belongsTo(Subsecretaria::class);
    }

    /**
     * Departamentos que pertenecen a esta dirección.
     */
    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    /**
     * Personas asignadas directamente a esta dirección mediante relación polimórfica.
     */
    public function personas(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Persona::class, 'dependencia');
    }

    /**
     * Obtener la dependencia padre (Subsecretaria).
     */
    public function getPadre()
    {
        return $this->subsecretaria;
    }

    /**
     * Obtener la jerarquía completa hasta la Secretaría.
     */
    public function getJerarquiaCompleta(): array
    {
        $jerarquia = [$this];
        
        if ($subsecretaria = $this->subsecretaria) {
            $jerarquia = array_merge($subsecretaria->getJerarquiaCompleta(), $jerarquia);
        }
        
        return $jerarquia;
    }
}
