<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectStudent extends Model
{
    protected $table = 'student_subjects';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'subject_id', 'student_id'
    ];

    public $timestamps = false;
    
    public function student()
    {
    	return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
    	return $this->belongsTo(User::class, 'student_id');
    }
}
