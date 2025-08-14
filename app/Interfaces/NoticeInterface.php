<?php

namespace App\Interfaces;

interface NoticeInterface
{
    public function create($request);
    public function getAll($session_id);
    public function findById($notice_id);
    public function update($request);
    public function delete($notice_id);
}
