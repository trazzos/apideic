<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';

    protected $guarded = [];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'responsable_id');
    }

    public function user()
    {
        return $this->morphOne(User::class, 'owner');
    }
}
