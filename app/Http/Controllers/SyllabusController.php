<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Http\Requests\StoreFileRequest;
use App\Interfaces\SchoolClassInterface;
use App\Repositories\SyllabusRepository;
use App\Interfaces\SchoolSessionInterface;

class SyllabusController extends Controller
{
    use SchoolSession;
    
    protected $schoolSessionRepository;
    protected $schoolClassRepository;
    
    private const NOT_IMPLEMENTED_MESSAGE = 'Method not implemented';

    public function __construct(
        SchoolSessionInterface $schoolSessionRepository,
        SchoolClassInterface $schoolClassRepository
    ) {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        $course_id = $request->query('course_id', 0);
        $syllabusRepository = new SyllabusRepository();
        $syllabus = $syllabusRepository->getByCourse($course_id);

        $data = [
            'syllabus'   => $syllabus
        ];

        return view('syllabus.show', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'school_classes'    => $school_classes,
        ];
        return view('syllabus.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreFileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreFileRequest $request)
    {
        $validatedRequest = $request->validated();
        $validatedRequest['session_id'] = $this->getSchoolCurrentSession();

        // Add additional fields from request
        $validatedRequest['class_id'] = $request->class_id ?? null;
        $validatedRequest['course_id'] = $request->course_id ?? null;
        $validatedRequest['syllabus_name'] = $request->syllabus_name ?? null;

        try {
            $syllabusRepository = new SyllabusRepository();
            $syllabusRepository->store($validatedRequest);

            return back()->with('status', 'Creating syllabus was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Syllabus $syllabus)
    {
        return response()->json(['message' => self::NOT_IMPLEMENTED_MESSAGE]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Syllabus $syllabus)
    {
        return response()->json(['message' => self::NOT_IMPLEMENTED_MESSAGE]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Syllabus $syllabus)
    {
        return response()->json(['message' => self::NOT_IMPLEMENTED_MESSAGE]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Syllabus $syllabus)
    {
        return response()->json(['message' => self::NOT_IMPLEMENTED_MESSAGE]);
    }
}
