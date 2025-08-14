<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\UserInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\CourseInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Traits\SchoolSession;

class GlobalSearchController extends Controller
{
    use SchoolSession;

    protected $userRepository;
    protected $schoolClassRepository;
    protected $sectionRepository;
    protected $courseRepository;
    protected $schoolSessionRepository;

    public function __construct(
        UserInterface $userRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $sectionRepository,
        CourseInterface $courseRepository,
        SchoolSessionInterface $schoolSessionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->sectionRepository = $sectionRepository;
        $this->courseRepository = $courseRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
    }

    /**
     * Handle AJAX search requests
     */
    public function ajaxSearch(Request $request)
    {
        $query = $request->get('query');
        $results = [];

        if (strlen($query) < 2) {
            return response()->json($results);
        }

        try {
            $current_school_session_id = $this->getSchoolCurrentSession();

            // Search Students
            $students = $this->userRepository->searchStudents($query, $current_school_session_id);
            foreach ($students as $student) {
                $results[] = [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'type' => 'Student',
                    'details' => $student->email ?? 'No email',
                    'url' => route('student.list'),
                    'icon' => 'bi-person-circle'
                ];
            }

            // Search Teachers
            $teachers = $this->userRepository->searchTeachers($query);
            foreach ($teachers as $teacher) {
                $results[] = [
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'type' => 'Teacher',
                    'details' => $teacher->email ?? 'No email',
                    'url' => route('teacher.list'),
                    'icon' => 'bi-person-badge'
                ];
            }

            // Search Classes (only if we have a session)
            if ($current_school_session_id) {
                $classes = $this->schoolClassRepository->searchClasses($query, $current_school_session_id);
                foreach ($classes as $class) {
                    $results[] = [
                        'name' => $class->class_name,
                        'type' => 'Class',
                        'details' => 'Academic Class',
                        'url' => route('class.list'),
                        'icon' => 'bi-diagram-3'
                    ];
                }

                // Search Courses (only if we have a session)
                $courses = $this->courseRepository->searchCourses($query, $current_school_session_id);
                foreach ($courses as $course) {
                    $results[] = [
                        'name' => $course->course_name,
                        'type' => 'Course',
                        'details' => $course->course_type ?? 'No type',
                        'url' => route('course.edit', $course->id),
                        'icon' => 'bi-book'
                    ];
                }
            }

            // Limit results to prevent overwhelming UI
            $results = array_slice($results, 0, 8);

        } catch (\Exception $e) {
            // Log the error for debugging but don't expose it to user
            Log::error('Search error: ' . $e->getMessage());
            
            // Return empty results instead of error
            $results = [];
        }

        return response()->json($results);
    }

    /**
     * Handle full search page
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $results = [
            'students' => [],
            'teachers' => [],
            'classes' => [],
            'courses' => []
        ];

        if (strlen($query) >= 2) {
            try {
                $current_school_session_id = $this->getSchoolCurrentSession();

                $results['students'] = $this->userRepository->searchStudents($query, $current_school_session_id);
                $results['teachers'] = $this->userRepository->searchTeachers($query);
                $results['classes'] = $this->schoolClassRepository->searchClasses($query, $current_school_session_id);
                $results['courses'] = $this->courseRepository->searchCourses($query, $current_school_session_id);

            } catch (\Exception $e) {
                return back()->withError('Search failed. Please try again.');
            }
        }

        $data = [
            'query' => $query,
            'results' => $results,
            'total_results' => array_sum(array_map('count', $results))
        ];

        return view('search.results', $data);
    }
}
