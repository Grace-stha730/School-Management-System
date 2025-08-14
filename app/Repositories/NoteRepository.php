<?php

namespace App\Repositories;

use App\Models\Note;
use App\Interfaces\NoteInterface;
use Illuminate\Support\Facades\Storage;

class NoteRepository implements NoteInterface {
    public function create($request) {
        $path = Storage::disk('public')->put('notes', $request['file']);
        try {
            Note::create([
                'note_name'         => $request['note_name'],
                'note_file_path'    => $path,
                'teacher_id'        => auth()->user()->id,
                'class_id'          => $request['class_id'],
                'section_id'        => $request['section_id'],
                'course_id'         => $request['course_id'],
                'semester_id'       => $request['semester_id'],
                'session_id'        => $request['session_id']
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create note. '.$e->getMessage());
        }
    }

    public function getAll($user_id) {
        return Note::where('teacher_id', $user_id)
                    ->orderBy('created_at','desc')
                    ->get();
    }

    public function findById($note_id) { 
        return Note::find($note_id); 
    }

    public function update($request) {
        try {
            Note::find($request->note_id)->update([
                'note_name' => $request->note_name,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'course_id' => $request->course_id,
                'semester_id' => $request->semester_id,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update note. '.$e->getMessage());
        }
    }

    public function delete($note_id) {
        try {
            $note = Note::find($note_id);
            if ($note && $note->note_file_path) {
                Storage::disk('public')->delete($note->note_file_path);
            }
            $note->delete();
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete note. '.$e->getMessage());
        }
    }

    // Legacy method for backward compatibility
    public function store($request) {
        return $this->create($request);
    }

    public function getNotes($session_id, $course_id) {
        return Note::where('course_id', $course_id)
                    ->where('session_id', $session_id)
                    ->orderBy('created_at','desc')
                    ->get();
    }
}
