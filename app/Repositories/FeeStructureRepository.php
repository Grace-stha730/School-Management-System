<?php

namespace App\Repositories;

use App\Models\FeeStructure;
use App\Interfaces\FeeStructureInterface;

class FeeStructureRepository implements FeeStructureInterface {
    
    public function getAll($session_id, $class_id = null, $section_id = null) {
        try {
            $query = FeeStructure::with(['session', 'schoolClass', 'section', 'feeHead'])
                                ->where(function($q) use ($session_id) {
                                    $q->where('session_id', $session_id)
                                      ->orWhereNull('session_id'); // Global structures
                                });
                                
            if ($class_id) {
                $query->forClass($class_id);
            }
            
            if ($section_id) {
                $query->forSection($section_id);
            }
            
            return $query->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fee structures. '.$e->getMessage());
        }
    }
    
    public function getActive($session_id, $class_id = null, $section_id = null) {
        try {
            $query = FeeStructure::with(['session', 'schoolClass', 'section', 'feeHead'])
                                ->active()
                                ->forSession($session_id);
                                
            if ($class_id) {
                $query->forClass($class_id);
            }
            
            if ($section_id) {
                $query->forSection($section_id);
            }
            
            return $query->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get active fee structures. '.$e->getMessage());
        }
    }
    
    public function findById($id) {
        try {
            return FeeStructure::with(['session', 'schoolClass', 'section', 'feeHead'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find fee structure. '.$e->getMessage());
        }
    }
    
    public function store($request) {
        try {
            return FeeStructure::create([
                'session_id' => $request['session_id'],
                'class_id' => $request['class_id'] ?? null,
                'section_id' => $request['section_id'] ?? null,
                'fee_head_id' => $request['fee_head_id'],
                'amount' => $request['amount'],
                'due_date' => $request['due_date'] ?? null,
                'is_active' => $request['is_active'] ?? true,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create fee structure. '.$e->getMessage());
        }
    }
    
    public function update($request, $id) {
        try {
            $feeStructure = FeeStructure::findOrFail($id);
            $feeStructure->update([
                'class_id' => $request['class_id'] ?? null,
                'section_id' => $request['section_id'] ?? null,
                'fee_head_id' => $request['fee_head_id'],
                'amount' => $request['amount'],
                'due_date' => $request['due_date'] ?? null,
                'is_active' => $request['is_active'] ?? true,
            ]);
            return $feeStructure;
        } catch (\Exception $e) {
            throw new \Exception('Failed to update fee structure. '.$e->getMessage());
        }
    }
    
    public function delete($id) {
        try {
            $feeStructure = FeeStructure::findOrFail($id);
            return $feeStructure->delete();
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete fee structure. '.$e->getMessage());
        }
    }
    
    public function getByClass($session_id, $class_id) {
        try {
            return FeeStructure::with(['feeHead', 'section'])
                              ->active()
                              ->forSession($session_id)
                              ->forClass($class_id)
                              ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fee structures by class. '.$e->getMessage());
        }
    }
    
    public function getBySection($session_id, $class_id, $section_id) {
        try {
            return FeeStructure::with(['feeHead'])
                              ->active()
                              ->forSession($session_id)
                              ->forClass($class_id)
                              ->forSection($section_id)
                              ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fee structures by section. '.$e->getMessage());
        }
    }
}
