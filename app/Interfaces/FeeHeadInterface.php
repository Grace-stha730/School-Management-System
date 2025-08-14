<?php

namespace App\Interfaces;

interface FeeHeadInterface {
    public function getAll($session_id);
    
    public function getActive($session_id);
    
    public function findById($id);
    
    public function store($request);
    
    public function update($request, $id);
    
    public function delete($id);
}
