<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Secretaria extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'secretarias';

    protected $guarded = [];

    /**
     * Subsecretarias que pertenecen a esta secretaria.
     */
    public function subsecretarias()
    {
        return $this->hasMany(Subsecretaria::class);
    }

    /**
     * Personas asignadas directamente a esta secretaria mediante relación polimórfica.
     */
    public function personas(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Persona::class, 'dependencia');
    }

    /**
     * Obtener la dependencia padre (ninguna, es el nivel superior).
     */
    public function getPadre()
    {
        return null; // Las secretarías son el nivel superior
    }

    /**
     * Obtener la jerarquía completa (solo ella misma).
     */
    public function getJerarquiaCompleta(): array
    {
        return [$this]; // Las secretarías son el nivel superior
    }
}
