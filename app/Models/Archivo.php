<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archivo extends Model
{
    use SoftDeletes;

    protected $table = 'archivos';

    protected $guarded = [];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }
    public function archivable()
    {
        return $this->morphTo();
    }

}
