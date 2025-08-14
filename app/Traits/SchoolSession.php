<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait SchoolSession {
    /**
     * @param string $request
     * 
     * @return string
    */
    public function getSchoolCurrentSession() {
        $current_school_session_id = 0;

        if (session()->has('browse_session_id')){
            $current_school_session_id = session('browse_session_id');
        } else {
            $latest_school_session = $this->schoolSessionRepository->getLatestSession();

            if($latest_school_session && $latest_school_session->id > 0){
                $current_school_session_id = $latest_school_session->id;
            } else {
                // If no session exists, create a default one
                $this->createDefaultSession();
                $latest_school_session = $this->schoolSessionRepository->getLatestSession();
                if($latest_school_session && $latest_school_session->id > 0){
                    $current_school_session_id = $latest_school_session->id;
                }
            }
        }

        return $current_school_session_id;
    }

    /**
     * Create a default academic session if none exists
     */
    private function createDefaultSession() {
        try {
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            $sessionName = $currentYear . '-' . $nextYear;
            
            // Check if this session already exists
            $existingSession = \App\Models\SchoolSession::where('session_name', $sessionName)->first();
            if (!$existingSession) {
                $this->schoolSessionRepository->create(['session_name' => $sessionName]);
            }
        } catch (\Exception $e) {
            // If creation fails, log but don't throw exception to avoid breaking the flow
            Log::error('Failed to create default session: ' . $e->getMessage());
        }
    }
}