<?php

namespace App\Repositories;

use App\Models\StudentFee;
use App\Models\User;
use App\Models\Promotion;
use App\Interfaces\StudentFeeInterface;

class StudentFeeRepository implements StudentFeeInterface {
    
    public function getStudentFees($student_id, $session_id) {
        try {
            return StudentFee::with(['feeStructure.feeHead', 'feeStructure.schoolClass', 'feeStructure.section'])
                            ->forStudent($student_id)
                            ->forSession($session_id)
                            ->orderBy('due_date')
                            ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get student fees. '.$e->getMessage());
        }
    }
    
    public function getAll($session_id, $class_id = null, $section_id = null) {
        try {
            $query = StudentFee::with(['student', 'feeStructure.feeHead', 'feeStructure.schoolClass', 'feeStructure.section'])
                              ->forSession($session_id);
                              
            if ($class_id || $section_id) {
                $query->whereHas('feeStructure', function($q) use ($class_id, $section_id) {
                    if ($class_id) $q->where('class_id', $class_id);
                    if ($section_id) $q->where('section_id', $section_id);
                });
            }
            
            return $query->orderBy('due_date')->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get student fees. '.$e->getMessage());
        }
    }
    
    public function findById($id) {
        try {
            return StudentFee::with(['student', 'feeStructure.feeHead', 'session'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find student fee. '.$e->getMessage());
        }
    }
    
    public function assignFeesToStudent($student_id, $fee_structure_ids) {
        try {
            $fees = [];
            foreach ($fee_structure_ids as $fee_structure_id) {
                $feeStructure = \App\Models\FeeStructure::findOrFail($fee_structure_id);
                
                $existingFee = StudentFee::where('student_id', $student_id)
                                       ->where('fee_structure_id', $fee_structure_id)
                                       ->first();
                                       
                if (!$existingFee) {
                    $fees[] = StudentFee::create([
                        'student_id' => $student_id,
                        'session_id' => $feeStructure->session_id,
                        'fee_structure_id' => $fee_structure_id,
                        'assigned_amount' => $feeStructure->amount,
                        'due_date' => $feeStructure->due_date,
                        'payment_status' => 'pending'
                    ]);
                }
            }
            return $fees;
        } catch (\Exception $e) {
            throw new \Exception('Failed to assign fees to student. '.$e->getMessage());
        }
    }
    
    public function updatePayment($id, $payment_data) {
        try {
            $studentFee = StudentFee::findOrFail($id);
            
            $newPaidAmount = $studentFee->paid_amount + $payment_data['amount'];
            $remainingAmount = $studentFee->assigned_amount - $newPaidAmount - $studentFee->discount_amount;
            
            $status = 'pending';
            if ($remainingAmount <= 0) {
                $status = 'paid';
            } elseif ($newPaidAmount > 0) {
                $status = 'partial';
            }
            
            $studentFee->update([
                'paid_amount' => $newPaidAmount,
                'payment_status' => $status,
                'paid_date' => $status === 'paid' ? now() : $studentFee->paid_date,
                'remarks' => $payment_data['remarks'] ?? $studentFee->remarks
            ]);
            
            return $studentFee;
        } catch (\Exception $e) {
            throw new \Exception('Failed to update payment. '.$e->getMessage());
        }
    }
    
    public function markAsPaid($id) {
        try {
            $studentFee = StudentFee::findOrFail($id);
            $studentFee->update([
                'paid_amount' => $studentFee->assigned_amount - $studentFee->discount_amount,
                'payment_status' => 'paid',
                'paid_date' => now()
            ]);
            return $studentFee;
        } catch (\Exception $e) {
            throw new \Exception('Failed to mark fee as paid. '.$e->getMessage());
        }
    }
    
    public function addDiscount($id, $discount_amount) {
        try {
            $studentFee = StudentFee::findOrFail($id);
            $remainingAmount = $studentFee->assigned_amount - $studentFee->paid_amount - $discount_amount;
            
            $status = $studentFee->payment_status;
            if ($remainingAmount <= 0) {
                $status = 'paid';
            }
            
            $studentFee->update([
                'discount_amount' => $discount_amount,
                'payment_status' => $status,
                'paid_date' => $status === 'paid' ? now() : $studentFee->paid_date
            ]);
            
            return $studentFee;
        } catch (\Exception $e) {
            throw new \Exception('Failed to add discount. '.$e->getMessage());
        }
    }
    
    public function getPendingFees($student_id, $session_id) {
        try {
            return StudentFee::with(['feeStructure.feeHead'])
                            ->forStudent($student_id)
                            ->forSession($session_id)
                            ->pending()
                            ->orderBy('due_date')
                            ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get pending fees. '.$e->getMessage());
        }
    }
    
    public function getOverdueFees($student_id, $session_id) {
        try {
            return StudentFee::with(['feeStructure.feeHead'])
                            ->forStudent($student_id)
                            ->forSession($session_id)
                            ->overdue()
                            ->orderBy('due_date')
                            ->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get overdue fees. '.$e->getMessage());
        }
    }
    
    public function assignFeesToClass($session_id, $class_id, $section_id = null) {
        try {
            // Get all students in the class/section
            $query = Promotion::where('session_id', $session_id)
                             ->where('class_id', $class_id);
                             
            if ($section_id) {
                $query->where('section_id', $section_id);
            }
            
            $students = $query->get();
            
            // Get fee structures for the class/section
            $feeStructures = \App\Models\FeeStructure::active()
                                                   ->forSession($session_id)
                                                   ->forClass($class_id);
                                                   
            if ($section_id) {
                $feeStructures->forSection($section_id);
            }
            
            $feeStructures = $feeStructures->get();
            
            $assignedFees = [];
            foreach ($students as $student) {
                foreach ($feeStructures as $feeStructure) {
                    $existingFee = StudentFee::where('student_id', $student->student_id)
                                           ->where('fee_structure_id', $feeStructure->id)
                                           ->first();
                                           
                    if (!$existingFee) {
                        $assignedFees[] = StudentFee::create([
                            'student_id' => $student->student_id,
                            'session_id' => $session_id,
                            'fee_structure_id' => $feeStructure->id,
                            'assigned_amount' => $feeStructure->amount,
                            'due_date' => $feeStructure->due_date,
                            'payment_status' => 'pending'
                        ]);
                    }
                }
            }
            
            return $assignedFees;
        } catch (\Exception $e) {
            throw new \Exception('Failed to assign fees to class. '.$e->getMessage());
        }
    }
}
