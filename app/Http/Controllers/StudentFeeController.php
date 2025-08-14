<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\StudentFeeInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\FeeStructureInterface;
use App\Interfaces\SchoolClassInterface;

class StudentFeeController extends Controller
{
    use SchoolSession;
    
    protected $studentFeeRepository;
    protected $userRepository;
    protected $schoolSessionRepository;
    protected $feeStructureRepository;
    protected $schoolClassRepository;

    public function __construct(
        StudentFeeInterface $studentFeeRepository,
        UserInterface $userRepository,
        SchoolSessionInterface $schoolSessionRepository,
        FeeStructureInterface $feeStructureRepository,
        SchoolClassInterface $schoolClassRepository
    ) {
        $this->studentFeeRepository = $studentFeeRepository;
        $this->userRepository = $userRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->feeStructureRepository = $feeStructureRepository;
        $this->schoolClassRepository = $schoolClassRepository;
    }

    public function index()
    {
        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            $studentFees = $this->studentFeeRepository->getAll($current_school_session_id);
            $feeStructures = $this->feeStructureRepository->getActive($current_school_session_id);
            $schoolClasses = $this->schoolClassRepository->getAllBySession($current_school_session_id);
            $students = $this->userRepository->getAllStudentsBySession($current_school_session_id);
            
            $data = [
                'current_school_session_id' => $current_school_session_id,
                'student_fees' => $studentFees,
                'fee_structures' => $feeStructures,
                'school_classes' => $schoolClasses,
                'students' => $students
            ];
            
            return view('student-fees.index', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show($student_id)
    {
        try {
            // Check if user can view this student's fees
            if(auth()->user()->role == "student" && auth()->user()->id != $student_id) {
                return abort(403, 'Unauthorized access to student fees.');
            }

            $current_school_session_id = $this->getSchoolCurrentSession();
            $student = $this->userRepository->findStudent($student_id);
            $studentFees = $this->studentFeeRepository->getStudentFees($student_id, $current_school_session_id);
            
            $data = [
                'current_school_session_id' => $current_school_session_id,
                'student' => $student,
                'student_fees' => $studentFees
            ];
            
            return view('student-fees.show', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:255'
        ]);

        try {
            $this->studentFeeRepository->updatePayment($id, $request->all());
            
            return back()->withSuccess('Payment updated successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function addDiscount(Request $request, $id)
    {
        $request->validate([
            'discount_amount' => 'required|numeric|min:0'
        ]);

        try {
            $this->studentFeeRepository->addDiscount($id, $request->discount_amount);
            
            return back()->withSuccess('Discount applied successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        $request->validate([
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'assignment_type' => 'required|in:class,individual',
            'class_id' => 'required_if:assignment_type,class|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'student_id' => 'required_if:assignment_type,individual|exists:users,id'
        ]);

        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            
            if ($request->assignment_type === 'individual') {
                // Assign fee to individual student
                $this->studentFeeRepository->assignFeesToStudent(
                    $request->student_id,
                    [$request->fee_structure_id]
                );
                return back()->withSuccess('Fee assigned to student successfully!');
            } else {
                // Assign fee to entire class - get all students and assign individually
                $query = \App\Models\Promotion::where('session_id', $current_school_session_id)
                                             ->where('class_id', $request->class_id);
                                             
                if ($request->section_id) {
                    $query->where('section_id', $request->section_id);
                }
                
                $students = $query->pluck('student_id');
                
                foreach ($students as $student_id) {
                    $this->studentFeeRepository->assignFeesToStudent(
                        $student_id,
                        [$request->fee_structure_id]
                    );
                }
                
                return back()->withSuccess('Fee assigned to class successfully!');
            }
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
