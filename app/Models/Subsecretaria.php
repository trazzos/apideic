<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subsecretaria extends Model
{
    use SoftDeletes;

    protected $table = 'subsecretarias';

    protected $guarded = [];

    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class);
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }
}
