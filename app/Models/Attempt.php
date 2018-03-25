<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
	const COMPLETED = 1,
			NOT_COMPLETED = 0;
			
	public $table = 'attempts';

	public $fillable = [
		'quiz_id', 'created_at', 'updated_at', 'expiration'
	];
}
