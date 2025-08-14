<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Interfaces\AssignmentInterface;
use Illuminate\Support\Facades\Storage;

class AssignmentRepository implements AssignmentInterface {
    public function store($request) {
        // Automatically generate a unique ID for filename...
        $path = Storage::disk('public')->put('assignments', $request['file']);
        try {
            Assignment::create([
                'assignment_name'           => $request['assignment_name'],
                'assignment_file_path'      => $path,
                'teacher_id'                => auth()->user()->id,
                'class_id'                  => $request['class_id'],
                'section_id'                => $request['section_id'],
                'course_id'                 => $request['course_id'],
                'semester_id'               => $request['semester_id'],
                'session_id'                => $request['session_id']
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create assignment. '.$e->getMessage());
        }
    }

    public function getAll($session_id) {
        return Assignment::where('session_id', $session_id)->get();
    }

    public function findById($assignment_id) {
        return Assignment::find($assignment_id);
    }

    public function update($request) {
        try {
            Assignment::find($request->assignment_id)->update([
                'assignment_name' => $request->assignment_name,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'course_id' => $request->course_id,
                'semester_id' => $request->semester_id,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update assignment. '.$e->getMessage());
        }
    }

    public function delete($assignment_id) {
        try {
            $assignment = Assignment::find($assignment_id);
            if ($assignment && $assignment->assignment_file_path) {
                Storage::disk('public')->delete($assignment->assignment_file_path);
            }
            $assignment->delete();
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete assignment. '.$e->getMessage());
        }
    }

    public function getAssignments($session_id, $course_id) {
        return Assignment::where('course_id', $course_id)
                        ->where('session_id', $session_id)
                        ->get();
    }
}