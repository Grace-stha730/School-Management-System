<?php

namespace App\Repositories;

use App\Models\FeeHead;
use App\Interfaces\FeeHeadInterface;

class FeeHeadRepository implements FeeHeadInterface {
    
    public function getAll($session_id) {
        try {
            // Show fee heads for the current/browsing session AND global fee heads (session_id = null)
            return FeeHead::with('session')
                          ->where(function($query) use ($session_id) {
                              $query->where('session_id', $session_id)
                                    ->orWhereNull('session_id');
                          })
                          ->orderBy('name')
                          ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fee heads. '.$e->getMessage());
        }
    }
    
    public function getActive($session_id) {
        try {
            // Show active fee heads for the current/browsing session AND global fee heads
            return FeeHead::active()
                          ->where(function($query) use ($session_id) {
                              $query->where('session_id', $session_id)
                                    ->orWhereNull('session_id');
                          })
                          ->orderBy('name')
                          ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get active fee heads. '.$e->getMessage());
        }
    }
    
    public function findById($id) {
        try {
            return FeeHead::with(['session', 'feeStructures'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find fee head. '.$e->getMessage());
        }
    }
    
    public function store($request) {
        try {
            return FeeHead::create([
                'name' => $request['name'],
                'description' => $request['description'] ?? null,
                'fee_type' => $request['fee_type'],
                'is_active' => $request['is_active'] ?? true,
                'session_id' => $request['session_id']
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create fee head. '.$e->getMessage());
        }
    }
    
    public function update($request, $id) {
        try {
            $feeHead = FeeHead::findOrFail($id);
            $feeHead->update([
                'name' => $request['name'],
                'description' => $request['description'] ?? null,
                'fee_type' => $request['fee_type'],
                'is_active' => $request['is_active'] ?? true,
            ]);
            return $feeHead;
        } catch (\Exception $e) {
            throw new \Exception('Failed to update fee head. '.$e->getMessage());
        }
    }
    
    public function delete($id) {
        try {
            $feeHead = FeeHead::findOrFail($id);
            return $feeHead->delete();
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete fee head. '.$e->getMessage());
        }
    }
}
