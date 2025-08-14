<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\SemesterInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Http\Requests\TeacherAssignRequest;
use App\Repositories\AssignedTeacherRepository;

class AssignedTeacherController extends Controller
{
    use SchoolSession;
    protected $schoolSessionRepository;
    protected $semesterRepository;

    /**
    * Create a new Controller instance
    * 
    * @param SchoolSessionInterface $schoolSessionRepository
    * @return void
    */
    public function __construct(SchoolSessionInterface $schoolSessionRepository,
    SemesterInterface $semesterRepository) {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->semesterRepository = $semesterRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['message' => 'Method not implemented']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function getTeacherCourses(Request $request)
    {
        try {
            $teacher_id = auth()->user()->id;
            $semester_id = $request->query('semester_id');
            
            $assignedTeacherRepository = new AssignedTeacherRepository();
            
            // If no semester is selected, get the current semester
            if (!$semester_id) {
                $current_session = $this->getSchoolCurrentSession();
                // Get the current or most recent semester
                $semester = \App\Models\Semester::where('session_id', $current_session)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $semester_id = $semester ? $semester->id : null;
            }
            
            $current_session = $this->getSchoolCurrentSession();
            $courses = $assignedTeacherRepository->getTeacherCourses($current_session, $teacher_id, $semester_id);
            
            // Get all semesters for the filter dropdown
            $semesters = \App\Models\Semester::where('session_id', $current_session)->get();
            
            $data = [
                'courses' => $courses,
                'selected_semester_id' => $semester_id,
                'course_count' => count($courses),
                'semesters' => $semesters
            ];

            return view('courses.teacher', $data);
        } catch (\Exception $e) {
            return back()->withError('Error loading courses: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TeacherAssignRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TeacherAssignRequest $request)
    {
        try {
            $assignedTeacherRepository = new AssignedTeacherRepository();
            $assignedTeacherRepository->assign($request->validated());

            return back()->with('status', 'Assigning teacher was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
