<?php

namespace App\Interfaces;

interface SyllabusInterface
{
    public function create($request);
    public function getAll($session_id);
    public function findById($syllabus_id);
    public function update($request);
    public function delete($syllabus_id);
}
