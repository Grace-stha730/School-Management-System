<?php

namespace App\Interfaces;

interface FeeStructureInterface {
    public function getAll($session_id, $class_id = null, $section_id = null);
    
    public function getActive($session_id, $class_id = null, $section_id = null);
    
    public function findById($id);
    
    public function store($request);
    
    public function update($request, $id);
    
    public function delete($id);
    
    public function getByClass($session_id, $class_id);
    
    public function getBySection($session_id, $class_id, $section_id);
}
