<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Subject extends Model
{
    protected $table = 'subjects';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'teacher_id'
    ];

    public $timestamps = false;

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'subject_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'subject_modules');
    }
}
