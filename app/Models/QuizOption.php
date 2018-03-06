<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
	protected $table = 'quiz_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_item_id', 'content', 'is_correct'
    ];


    public $timestamps = false;
}
