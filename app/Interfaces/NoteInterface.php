<?php

namespace App\Interfaces;

interface NoteInterface
{
    public function create($request);
    public function getAll($user_id);
    public function findById($note_id);
    public function update($request);
    public function delete($note_id);
}
