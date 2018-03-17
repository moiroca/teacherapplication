<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
	use Notifiable;
	use HasRoles;
    use SoftDeletes;
	
    const   CONFIRMED = 1,
            NOT_CONFIRMED = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * deleted_at attribute
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function isConfirmed()
    {
        return ($this->is_confirmed == 1) ? true : false;
    }
    
    public function isTeacher()
    {
        return ($this->role == 1) ? true : false;
    }

    public function isAdmin()
    {
        return ($this->role == 3) ? true : false;
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects', 'student_id', 'subject_id');
    }
}
