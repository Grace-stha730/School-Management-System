<?php

namespace App\Interfaces;

interface StudentAcademicInfoInterface
{
    public function create($request);
    public function getAll($session_id);
    public function findById($student_id);
    public function update($request);
    public function delete($student_id);
}
