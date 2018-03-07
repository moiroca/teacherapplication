<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuiz extends Model
{
    protected $table = 'student_quizzes'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'quiz_id'
    ];

    public $timestamps = false;
}
