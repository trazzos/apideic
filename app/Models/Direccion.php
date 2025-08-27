<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use SoftDeletes;

    protected $table = 'direcciones';

    protected $guarded = [];

    public function subsecretaria()
    {
        return $this->belongsTo(Subsecretaria::class);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }   
}
