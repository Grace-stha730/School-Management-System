<?php

namespace App\Interfaces;

interface StudentFeeInterface {
    public function getStudentFees($student_id, $session_id);
    
    public function getAll($session_id, $class_id = null, $section_id = null);
    
    public function findById($id);
    
    public function assignFeesToStudent($student_id, $fee_structure_ids);
    
    public function updatePayment($id, $payment_data);
    
    public function markAsPaid($id);
    
    public function addDiscount($id, $discount_amount);
    
    public function getPendingFees($student_id, $session_id);
    
    public function getOverdueFees($student_id, $session_id);
    
    public function assignFeesToClass($session_id, $class_id, $section_id = null);
}
