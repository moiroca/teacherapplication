<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    const   MULTIPLE_CHOICE = 1,
            IDENTIFICATION  = 2,
            EXAM            = 3,
            DRAFT           = 1,
            PUBLISHED       = 2;
            
    protected $table = 'quizzes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'subject_id', 'quiz_type', 'status'
    ];

    public $timestamps = false;

    public function subject()
    {
    	return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function items()
    {
    	return $this->belongsToMany(QuizItem::class, 'quiz_items_pivot', 'quiz_id', 'item_id');
    }

    public function isPublished()
    {
        return (self::PUBLISHED == $this->status) ? true : false; 
    }

    public function isDraft()
    {
        return (self::DRAFT == $this->status) ? true : false; 
    }
}
