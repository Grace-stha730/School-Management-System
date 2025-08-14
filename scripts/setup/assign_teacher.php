<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Assign Teacher to More Courses ===\n";

// Get current session and teacher
$currentSession = \App\Models\SchoolSession::latest()->first();
$teacher = \App\Models\User::where('role', 'teacher')->first();
$course = \App\Models\Course::first();
$class = \App\Models\SchoolClass::where('session_id', $currentSession->id)->first();
$section = \App\Models\Section::first();
$semester = \App\Models\Semester::where('session_id', $currentSession->id)->first();

if (!$currentSession || !$teacher || !$course || !$class || !$section || !$semester) {
    echo "❌ Missing required data for assignment\n";
    exit;
}

echo "Current Session: {$currentSession->session_name}\n";
echo "Teacher: {$teacher->name}\n";
echo "Course: {$course->course_name}\n";
echo "Class: {$class->class_name}\n";
echo "Section: {$section->section_name}\n";
echo "Semester: {$semester->semester_name}\n";

// Check if assignment already exists
$existingAssignment = \App\Models\AssignedTeacher::where([
    'teacher_id' => $teacher->id,
    'course_id' => $course->id,
    'class_id' => $class->id,
    'section_id' => $section->id,
    'semester_id' => $semester->id,
    'session_id' => $currentSession->id,
])->first();

if ($existingAssignment) {
    echo "✅ Assignment already exists for this combination\n";
} else {
    // Create new assignment
    try {
        \App\Models\AssignedTeacher::create([
            'teacher_id' => $teacher->id,
            'course_id' => $course->id,
            'class_id' => $class->id,
            'section_id' => $section->id,
            'semester_id' => $semester->id,
            'session_id' => $currentSession->id,
        ]);
        echo "✅ New assignment created successfully\n";
    } catch (Exception $e) {
        echo "❌ Error creating assignment: {$e->getMessage()}\n";
    }
}

// Show all assignments for teacher
echo "\n--- All Teacher Assignments ---\n";
$assignments = \App\Models\AssignedTeacher::with(['course', 'schoolClass', 'section', 'semester'])
    ->where('teacher_id', $teacher->id)
    ->get();

foreach ($assignments as $assignment) {
    echo "- {$assignment->course->course_name} | {$assignment->schoolClass->class_name} | {$assignment->section->section_name} | Semester: {$assignment->semester->semester_name} | Session: {$assignment->session_id}\n";
}

echo "\n=== Done ===\n";
