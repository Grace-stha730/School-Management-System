<?php

namespace App\Models;

use App\Models\Mark;
use App\Models\StudentParentInfo;
use App\Models\StudentAcademicInfo;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'nationality',
        'phone',
        'address',
        'address2',
        'city',
        'zip',
        'photo',
        'birthday',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Automatically assign basic permissions to students
            if ($user->role === 'student') {
                $user->givePermissionTo([
                    'view notes',
                    'view assignments',
                    'view syllabi',
                    'view marks'
                ]);
            }
        });
    }

    /**
     * Get the parent_info.
     */
    public function parent_info()
    {
        return $this->hasOne(StudentParentInfo::class, 'student_id', 'id');
    }

    /**
     * Get the academic_info.
     */
    public function academic_info()
    {
        return $this->hasOne(StudentAcademicInfo::class, 'student_id', 'id');
    }

    /**
     * Get the marks.
     */
    public function marks()
    {
        return $this->hasMany(Mark::class, 'student_id', 'id');
    }

    /**
     * Get the student fees.
     */
    public function studentFees()
    {
        return $this->hasMany(StudentFee::class, 'student_id', 'id');
    }

    /**
     * Get the assignments as teacher.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'teacher_id', 'id');
    }

    /**
     * Get the notes as teacher.
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'teacher_id', 'id');
    }

    /**
     * Get the assigned courses as teacher.
     */
    public function assignedCourses()
    {
        return $this->hasMany(AssignedTeacher::class, 'teacher_id', 'id');
    }

    /**
     * Get the promotions as student.
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'student_id', 'id');
    }

    /**
     * Check if user is a student.
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
