<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secretaria extends Model
{
    use SoftDeletes;

    protected $table = 'secretarias';

    protected $guarded = [];

    public function subsecretarias()
    {
        return $this->hasMany(Subsecretaria::class);
    }
}
