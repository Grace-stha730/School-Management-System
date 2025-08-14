<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'semester_id',
        'class_id',
        'section_id',
        'course_id',
        'session_id',
        'note_name',
        'note_file_path'
    ];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class, 'section_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
}
