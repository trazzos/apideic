<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autoridad extends Model
{
    use SoftDeletes;

    protected $table = 'autoridades';

    protected $guarded = [];
}
