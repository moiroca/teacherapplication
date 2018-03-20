<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
    
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

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_subjects', 'subject_id', 'student_id');
    }
}
