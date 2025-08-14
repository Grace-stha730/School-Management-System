<?php

namespace App\Providers;

use App\Interfaces\FeeHeadInterface;
use App\Interfaces\FeeStructureInterface;
use App\Interfaces\StudentFeeInterface;
use App\Repositories\FeeHeadRepository;
use App\Repositories\FeeStructureRepository;
use App\Repositories\StudentFeeRepository;
use Illuminate\Support\ServiceProvider;

class FeeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FeeHeadInterface::class, FeeHeadRepository::class);
        $this->app->bind(FeeStructureInterface::class, FeeStructureRepository::class);
        $this->app->bind(StudentFeeInterface::class, StudentFeeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
