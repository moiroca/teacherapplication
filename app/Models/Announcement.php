<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
	public $table = 'announcements';

	public $fillable = ['subject_id', 'content', 'created_at'];


	public function subject()
	{
		return $this->hasOne(Subject::class, 'id', 'subject_id');
	}
}
