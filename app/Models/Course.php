<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['course_name', 'course_type', 'class_id', 'semester_id', 'session_id'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function assignedTeachers()
    {
        return $this->hasMany(AssignedTeacher::class);
    }

    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }
}
