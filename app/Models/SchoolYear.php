<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    public $table = 'school_years';

    public $fillable = ['from', 'to'];

    public $timestamps = false;
}
