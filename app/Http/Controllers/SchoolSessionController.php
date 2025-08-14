<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\SchoolSessionInterface;
use App\Http\Requests\SchoolSessionStoreRequest;
use App\Http\Requests\SchoolSessionBrowseRequest;

class SchoolSessionController extends Controller
{
    protected $schoolSessionRepository;

    /**
    * Create a new Controller instance
    * 
    * @param SchoolSessionInterface $schoolSessionRepository
    * @return void
    */
    public function __construct(SchoolSessionInterface $schoolSessionRepository) {
        $this->schoolSessionRepository = $schoolSessionRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SchoolSessionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolSessionStoreRequest $request)
    {
        try {
            $this->schoolSessionRepository->create($request->validated());

            return back()->with('status', 'Session creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
        
    }

    /**
     * Save the selected school session as current session for
     * browsing.
     *
     * @param  SchoolSessionBrowseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function browse(SchoolSessionBrowseRequest $request)
    {
        try {
            $this->schoolSessionRepository->browse($request->validated());

            return back()->with('status', 'Browsing session set was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
        
    }

    /**
     * Reset browsing session to current session
     *
     * @return \Illuminate\Http\Response
     */
    public function reset()
    {
        try {
            // Clear the browsing session
            session()->forget(['browse_session_id', 'browse_session_name']);
            
            return back()->with('status', 'Session reset to current successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Delete the specified academic session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $session = $this->schoolSessionRepository->getSessionById($id);
            
            if (!$session) {
                return back()->withError('Session not found!');
            }

            // Check if this is the latest session
            $latestSession = $this->schoolSessionRepository->getLatestSession();
            if ($latestSession->id == $id) {
                return back()->withError('Cannot delete the latest academic session!');
            }

            // Check if currently browsing this session
            if (session()->has('browse_session_id') && session('browse_session_id') == $id) {
                session()->forget(['browse_session_id', 'browse_session_name']);
            }

            $this->schoolSessionRepository->delete($id);

            return back()->with('status', 'Academic session deleted successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
