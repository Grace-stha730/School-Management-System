<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\FeeHeadInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Http\Requests\FeeHeadStoreRequest;
use App\Http\Requests\FeeHeadUpdateRequest;

class FeeHeadController extends Controller
{
    use SchoolSession;
    
    protected $feeHeadRepository;
    protected $schoolSessionRepository;

    public function __construct(FeeHeadInterface $feeHeadRepository, SchoolSessionInterface $schoolSessionRepository)
    {
        $this->feeHeadRepository = $feeHeadRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            $feeHeads = $this->feeHeadRepository->getAll($current_school_session_id);
            
            $data = [
                'current_school_session_id' => $current_school_session_id,
                'fee_heads' => $feeHeads
            ];
            
            return view('fees.heads.index', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            
            $data = [
                'current_school_session_id' => $current_school_session_id
            ];
            
            return view('fees.heads.create', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee_type' => 'required|in:monthly,quarterly,yearly,one_time,exam,transport,hostel,library,laboratory,other',
            'is_active' => 'boolean',
            'is_required' => 'boolean'
        ]);

        try {
            $current_school_session_id = $this->getSchoolCurrentSession();
            
            $requestData = $request->all();
            $requestData['session_id'] = $current_school_session_id;
            
            $this->feeHeadRepository->store($requestData);
            
            return redirect()->route('fees.heads.index')->withSuccess('Fee head created successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $feeHead = $this->feeHeadRepository->findById($id);
            
            $data = [
                'fee_head' => $feeHead
            ];
            
            return view('fees.heads.show', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $feeHead = $this->feeHeadRepository->findById($id);
            
            $data = [
                'fee_head' => $feeHead
            ];
            
            return view('fees.heads.edit', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee_type' => 'required|in:monthly,quarterly,yearly,one_time,exam,transport,hostel,library,laboratory,other',
            'is_active' => 'boolean',
            'is_required' => 'boolean'
        ]);

        try {
            $this->feeHeadRepository->update($request->all(), $id);
            
            return redirect()->route('fees.heads.index')->withSuccess('Fee head updated successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->feeHeadRepository->delete($id);
            
            return redirect()->route('fees.heads.index')->withSuccess('Fee head deleted successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
