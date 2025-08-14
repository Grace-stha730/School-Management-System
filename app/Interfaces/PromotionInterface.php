<?php

namespace App\Interfaces;

interface PromotionInterface
{
    public function create($request);
    public function getAll($session_id);
    public function findById($promotion_id);
    public function update($request);
    public function delete($promotion_id);
}
