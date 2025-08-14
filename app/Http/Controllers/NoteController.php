<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFileRequest;
use App\Repositories\NoteRepository;
use App\Traits\SchoolSession;
use App\Interfaces\SchoolSessionInterface;

class NoteController extends Controller
{
    use SchoolSession;

    protected $schoolSessionRepository;

    public function __construct(SchoolSessionInterface $schoolSessionRepository) {
        $this->schoolSessionRepository = $schoolSessionRepository;
    }

    public function create(Request $request) {
        $data = [
            'class_id' => $request->query('class_id'),
            'section_id' => $request->query('section_id'),
            'course_id' => $request->query('course_id'),
            'semester_id' => $request->query('semester_id'),
            'course_name' => $request->query('course_name'),
        ];
        return view('notes.create', $data);
    }

    public function store(StoreFileRequest $request) {
        $validated = $request->validated();
        $validated['session_id'] = $this->getSchoolCurrentSession();
        
        try {
            $repo = new NoteRepository();
            $repo->store($validated);
            return back()->with('status', 'Note upload was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function list(Request $request) {
        $course_id = $request->query('course_id', 0);
        $course_name = $request->query('course_name');
        $session_id = $this->getSchoolCurrentSession();
        $repo = new NoteRepository();
        
        if ($course_id) {
            // Show notes for specific course
            $notes = $repo->getNotes($session_id, $course_id);
        } else {
            // Show all notes based on user role
            if (auth()->user()->role === 'teacher') {
                // Show all notes created by the current teacher
                $notes = \App\Models\Note::with(['course', 'schoolClass', 'section'])
                    ->where('teacher_id', auth()->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // For students, show notes from their enrolled courses
                // First, get the student's current class and section
                $studentPromotion = \App\Models\Promotion::where('session_id', $session_id)
                    ->where('student_id', auth()->user()->id)
                    ->first();
                
                if ($studentPromotion) {
                    // Get courses assigned to the student's class
                    $studentCourses = \App\Models\AssignedTeacher::where('session_id', $session_id)
                        ->where('class_id', $studentPromotion->class_id)
                        ->where('section_id', $studentPromotion->section_id)
                        ->pluck('course_id');
                    
                    $notes = \App\Models\Note::with(['course', 'schoolClass', 'section'])
                        ->where('session_id', $session_id)
                        ->whereIn('course_id', $studentCourses)
                        ->orderBy('created_at', 'desc')
                        ->get();
                } else {
                    // Student not assigned to any class, show empty collection
                    $notes = collect([]);
                }
            }
            $course_name = null; // Clear course name for general view
        }
        
        return view('notes.list', [
            'notes' => $notes,
            'course_name' => $course_name,
            'course_id' => $course_id
        ]);
    }

    public function download($id) {
        $repo = new NoteRepository();
        $note = $repo->findById($id);
        if(!$note) { return back()->withError('Note not found'); }
        $path = storage_path('app/public/'.$note->note_file_path);
        if(!file_exists($path)) { return back()->withError('File not found'); }
        return response()->download($path, $note->note_name.'.'.pathinfo($path, PATHINFO_EXTENSION));
    }
}
