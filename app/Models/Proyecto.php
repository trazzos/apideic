<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos';

    protected $guarded = [];

    public function tipoProyecto(): BelongsTo
    {
        return $this->belongsTo(TipoProyecto::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
