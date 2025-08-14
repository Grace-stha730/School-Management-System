<?php

namespace App\Interfaces;

interface AssignmentInterface
{
    public function store($request);
    public function getAll($session_id);
    public function findById($assignment_id);
    public function update($request);
    public function delete($assignment_id);
}
