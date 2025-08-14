<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Teacher Dashboard Debug ===\n";

// Get teacher user
$teacher = \App\Models\User::where('role', 'teacher')->first();
if (!$teacher) {
    echo "❌ No teacher user found\n";
    exit;
}

echo "✅ Teacher found: {$teacher->name} ({$teacher->email})\n";
echo "Teacher ID: {$teacher->id}\n";

// Check teacher permissions
echo "\n--- Teacher Permissions ---\n";
$permissions = $teacher->getAllPermissions();
foreach ($permissions as $permission) {
    echo "✅ {$permission->name}\n";
}

if ($teacher->can('create notes')) {
    echo "✅ Teacher can create notes\n";
} else {
    echo "❌ Teacher CANNOT create notes\n";
}

if ($teacher->can('view notes')) {
    echo "✅ Teacher can view notes\n";
} else {
    echo "❌ Teacher CANNOT view notes\n";
}

// Check current session
$currentSession = \App\Models\SchoolSession::latest()->first();
echo "\n--- Current Session ---\n";
if ($currentSession) {
    echo "Current session: {$currentSession->session_name} (ID: {$currentSession->id})\n";
} else {
    echo "❌ No school session found\n";
    exit;
}

// Check semesters
echo "\n--- Semesters for Current Session ---\n";
$semesters = \App\Models\Semester::where('session_id', $currentSession->id)->get();
foreach ($semesters as $semester) {
    echo "Semester: {$semester->semester_name} (ID: {$semester->id})\n";
}

// Check assigned courses for teacher
echo "\n--- Assigned Courses for Teacher ---\n";
$assignedCourses = \App\Models\AssignedTeacher::with(['course', 'schoolClass', 'section'])
    ->where('teacher_id', $teacher->id)
    ->where('session_id', $currentSession->id)
    ->get();

if ($assignedCourses->count() === 0) {
    echo "❌ No courses assigned to teacher for current session\n";
    
    // Check if teacher has assignments in any session
    $allAssignments = \App\Models\AssignedTeacher::where('teacher_id', $teacher->id)->get();
    if ($allAssignments->count() > 0) {
        echo "⚠️  Teacher has assignments in other sessions:\n";
        foreach ($allAssignments as $assignment) {
            echo "  - Session: {$assignment->session_id}, Course: {$assignment->course_id}, Class: {$assignment->class_id}\n";
        }
    } else {
        echo "❌ Teacher has NO course assignments at all\n";
    }
} else {
    echo "✅ Teacher has {$assignedCourses->count()} course assignments:\n";
    foreach ($assignedCourses as $assignment) {
        echo "  - Course: {$assignment->course->course_name}\n";
        echo "    Class: {$assignment->schoolClass->class_name}\n";
        echo "    Section: {$assignment->section->section_name}\n";
        echo "    Semester: {$assignment->semester_id}\n";
        echo "    Session: {$assignment->session_id}\n\n";
    }
}

// Test the repository method
echo "\n--- Testing Repository Method ---\n";
try {
    $repo = new \App\Repositories\AssignedTeacherRepository();
    
    // Test with each semester
    foreach ($semesters as $semester) {
        $courses = $repo->getTeacherCourses($currentSession->id, $teacher->id, $semester->id);
        echo "Semester {$semester->semester_name}: {$courses->count()} courses found\n";
    }
} catch (Exception $e) {
    echo "❌ Error in repository: {$e->getMessage()}\n";
}

echo "\n=== End Debug ===\n";
