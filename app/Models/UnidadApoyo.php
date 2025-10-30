<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class UnidadApoyo extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'unidades_apoyo';

    protected $guarded = [];

    /**
     * Secretaria a la que pertenece esta unidad de apoyo.
     */
    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class);
    }

    /**
     * Personas asignadas directamente a esta unidad de apoyo mediante relación polimórfica.
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
}
