<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\FeeStructureInterface;
use App\Interfaces\FeeHeadInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\SemesterInterface;
use App\Interfaces\SchoolSessionInterface;

class FeeStructureController extends Controller
{
    use SchoolSession;
    
    protected $feeStructureRepository;
    protected $feeHeadRepository;
    protected $schoolClassRepository;
    protected $sectionRepository;
    protected $semesterRepository;
    protected $schoolSessionRepository;

    public function __construct(
        FeeStructureInterface $feeStructureRepository,
        FeeHeadInterface $feeHeadRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $sectionRepository,
        SemesterInterface $semesterRepository,
        SchoolSessionInterface $schoolSessionRepository
    ) {
        $this->feeStructureRepository = $feeStructureRepository;
        $this->feeHeadRepository = $feeHeadRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->sectionRepository = $sectionRepository;
        $this->semesterRepository = $semesterRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
    }

    public function index()
    {
        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            $feeStructures = $this->feeStructureRepository->getAll($current_school_session_id);
            
            $data = [
                'current_school_session_id' => $current_school_session_id,
                'fee_structures' => $feeStructures
            ];
            
            return view('fees.structures.index', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function create()
    {
        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            
            $feeHeads = $this->feeHeadRepository->getActive($current_school_session_id);
            $schoolClasses = $this->schoolClassRepository->getAllBySession($current_school_session_id);
            $sections = $this->sectionRepository->getAllBySession($current_school_session_id);
            $semesters = $this->semesterRepository->getAll($current_school_session_id);
            
            $data = [
                'current_school_session_id' => $current_school_session_id,
                'fee_heads' => $feeHeads,
                'school_classes' => $schoolClasses,
                'sections' => $sections,
                'semesters' => $semesters
            ];
            
            return view('fees.structures.create', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee_head_id' => 'required|exists:fee_heads,id',
            'amount' => 'required|numeric|min:0',
            'class_id' => 'nullable|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'due_date' => 'nullable|date|after:today'
        ]);

        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            
            $requestData = $request->all();
            $requestData['session_id'] = $current_school_session_id;
            
            $this->feeStructureRepository->store($requestData);
            
            return redirect()->route('fees.structures.index')->withSuccess('Fee structure created successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            
            $feeStructure = $this->feeStructureRepository->findById($id);
            $feeHeads = $this->feeHeadRepository->getActive($current_school_session_id);
            $schoolClasses = $this->schoolClassRepository->getAllBySession($current_school_session_id);
            $sections = $this->sectionRepository->getAllBySession($current_school_session_id);
            $semesters = $this->semesterRepository->getAll($current_school_session_id);
            
            $data = [
                'fee_structure' => $feeStructure,
                'fee_heads' => $feeHeads,
                'school_classes' => $schoolClasses,
                'sections' => $sections,
                'semesters' => $semesters
            ];
            
            return view('fees.structures.edit', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fee_head_id' => 'required|exists:fee_heads,id',
            'amount' => 'required|numeric|min:0',
            'class_id' => 'nullable|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'due_date' => 'nullable|date'
        ]);

        try {
            $this->feeStructureRepository->update($request->all(), $id);
            
            return redirect()->route('fees.structures.index')->withSuccess('Fee structure updated successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->feeStructureRepository->delete($id);
            return redirect()->route('fees.structures.index')->withSuccess('Fee structure deleted successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
