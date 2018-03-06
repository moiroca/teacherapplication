<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'subject_id'
    ];

    public $timestamps = false;

    public function inattendance()
    {
    	return $this->hasMany(StudentAttendance::class, 'attendance_id');
    }
}
