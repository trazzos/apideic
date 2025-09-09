<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Tarea extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'tareas';

    protected $guarded = [];

    protected $appends = ['estatus'];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }
    public function getEstatusAttribute(): string
    {
        return $this->completed_at ? 'Completada' : 'Pendiente';
    }
}
