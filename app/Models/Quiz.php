<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'subject_id'
    ];

    public $timestamps = false;

    public function subject()
    {
    	return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function items()
    {
    	return $this->hasMany(QuizItem::class, 'quiz_id');
    }
}
