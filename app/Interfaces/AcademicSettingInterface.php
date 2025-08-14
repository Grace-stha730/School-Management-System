<?php

namespace App\Interfaces;

interface AcademicSettingInterface {
    public function getAcademicSetting();

    public function updateFinalMarksSubmissionStatus($request);
}