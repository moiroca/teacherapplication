<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectStudent extends Model
{
    protected $table = 'student_subjects';
    
    public function student()
    {
    	return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
    	return $this->belongsTo(User::class, 'student_id');
    }
}
