<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizItemPivot extends Model
{
	public $table = 'quiz_items_pivot';
	public $fillable = ['quiz_id', 'item_id'];

	public $timestamps = false;
}
