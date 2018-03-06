<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    protected $table = 'quiz_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id', 'question'
    ];

    public $timestamps = false;


    public function options()
    {
    	return $this->hasMany(QuizOption::class, 'quiz_item_id');
    }
}
