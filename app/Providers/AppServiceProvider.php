<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Interface bindings
        $this->app->bind(\App\Interfaces\AcademicSettingInterface::class, \App\Repositories\AcademicSettingRepository::class);
        $this->app->bind(\App\Interfaces\AssignedTeacherInterface::class, \App\Repositories\AssignedTeacherRepository::class);
        $this->app->bind(\App\Interfaces\AssignmentInterface::class, \App\Repositories\AssignmentRepository::class);
        $this->app->bind(\App\Interfaces\CourseInterface::class, \App\Repositories\CourseRepository::class);
        $this->app->bind(\App\Interfaces\ExamInterface::class, \App\Repositories\ExamRepository::class);
        $this->app->bind(\App\Interfaces\ExamRuleInterface::class, \App\Repositories\ExamRuleRepository::class);
        $this->app->bind(\App\Interfaces\FeeHeadInterface::class, \App\Repositories\FeeHeadRepository::class);
        $this->app->bind(\App\Interfaces\FeeStructureInterface::class, \App\Repositories\FeeStructureRepository::class);
        $this->app->bind(\App\Interfaces\MarkInterface::class, \App\Repositories\MarkRepository::class);
        $this->app->bind(\App\Interfaces\NoteInterface::class, \App\Repositories\NoteRepository::class);
        $this->app->bind(\App\Interfaces\NoticeInterface::class, \App\Repositories\NoticeRepository::class);
        $this->app->bind(\App\Interfaces\PromotionInterface::class, \App\Repositories\PromotionRepository::class);
        $this->app->bind(\App\Interfaces\RoutineInterface::class, \App\Repositories\RoutineRepository::class);
        $this->app->bind(\App\Interfaces\SchoolClassInterface::class, \App\Repositories\SchoolClassRepository::class);
        $this->app->bind(\App\Interfaces\SchoolSessionInterface::class, \App\Repositories\SchoolSessionRepository::class);
        $this->app->bind(\App\Interfaces\SectionInterface::class, \App\Repositories\SectionRepository::class);
        $this->app->bind(\App\Interfaces\SemesterInterface::class, \App\Repositories\SemesterRepository::class);
        $this->app->bind(\App\Interfaces\StudentAcademicInfoInterface::class, \App\Repositories\StudentAcademicInfoRepository::class);
        $this->app->bind(\App\Interfaces\StudentFeeInterface::class, \App\Repositories\StudentFeeRepository::class);
        $this->app->bind(\App\Interfaces\SyllabusInterface::class, \App\Repositories\SyllabusRepository::class);
        $this->app->bind(\App\Interfaces\UserInterface::class, \App\Repositories\UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
