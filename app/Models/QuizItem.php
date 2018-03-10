<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    const   IDENTIFICATION = 1,
            MULTIPLE_CHOICE = 2;
            
    protected $table = 'quiz_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question', 'quiz_item_type'
    ];

    public $timestamps = false;


    public function options()
    {
    	return $this->hasMany(QuizOption::class, 'quiz_item_id');
    }

    public function correctOption()
    {
        return $this->options->where('is_correct', 1)->first();
    }
}
