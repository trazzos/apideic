<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use SoftDeletes;

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
