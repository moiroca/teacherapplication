<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectModule extends Model
{
    protected $table = 'subject_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_id', 'module_id'
    ];

    public $timestamps = false;
}
