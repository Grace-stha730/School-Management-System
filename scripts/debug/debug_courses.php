<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug Teacher Courses Issue ===\n";

// Check school sessions
$sessions = \App\Models\SchoolSession::all();
echo "School Sessions:\n";
foreach ($sessions as $session) {
    echo "- ID: {$session->id}, Name: {$session->session_name}\n";
}

// Check semesters
$semesters = \App\Models\Semester::all();
echo "\nSemesters:\n";
foreach ($semesters as $semester) {
    echo "- ID: {$semester->id}, Name: {$semester->semester_name}, Session: {$semester->session_id}\n";
}

// Check classes
$classes = \App\Models\SchoolClass::all();
echo "\nSchool Classes:\n";
foreach ($classes as $class) {
    echo "- ID: {$class->id}, Name: {$class->class_name}, Session: {$class->session_id}\n";
}

// Check courses
$courses = \App\Models\Course::all();
echo "\nCourses:\n";
foreach ($courses as $course) {
    echo "- ID: {$course->id}, Name: {$course->course_name}\n";
}

// Check sections
$sections = \App\Models\Section::all();
echo "\nSections:\n";
foreach ($sections as $section) {
    echo "- ID: {$section->id}, Name: {$section->section_name}\n";
}

// Check assigned teachers
$assignedTeachers = \App\Models\AssignedTeacher::all();
echo "\nAssigned Teachers:\n";
if ($assignedTeachers->count() === 0) {
    echo "- No assigned teachers found!\n";
} else {
    foreach ($assignedTeachers as $assigned) {
        echo "- Teacher: {$assigned->teacher_id}, Course: {$assigned->course_id}, Class: {$assigned->class_id}, Section: {$assigned->section_id}, Semester: {$assigned->semester_id}, Session: {$assigned->session_id}\n";
    }
}

// Check teacher user
$teacher = \App\Models\User::where('role', 'teacher')->first();
if ($teacher) {
    echo "\nTesting with teacher: {$teacher->name} (ID: {$teacher->id})\n";
    
    $currentSession = \App\Models\SchoolSession::latest()->first();
    if ($currentSession) {
        echo "Current session: {$currentSession->session_name} (ID: {$currentSession->id})\n";
        
        $firstSemester = \App\Models\Semester::where('session_id', $currentSession->id)->first();
        if ($firstSemester) {
            echo "First semester: {$firstSemester->semester_name} (ID: {$firstSemester->id})\n";
            
            // Try to get teacher courses
            $repo = new \App\Repositories\AssignedTeacherRepository();
            $courses = $repo->getTeacherCourses($currentSession->id, $teacher->id, $firstSemester->id);
            echo "Teacher courses found: " . $courses->count() . "\n";
        } else {
            echo "No semesters found for current session\n";
        }
    } else {
        echo "No school sessions found\n";
    }
}

echo "\n=== End Debug ===\n";
