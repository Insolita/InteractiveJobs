<?php

namespace App\Lib\InteractiveJobs;

use App\Lib\InteractiveJobs\Contracts\JobDefinitionRepository;
use App\Lib\InteractiveJobs\Models\Job;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class InteractiveJobsProvider extends ServiceProvider
{
    public function boot()
    {
        Job::observe(JobModelObserver::class);
        View::composer(['jobs._menu'], JobMenuComposer::class);
    }
    
    public function provides()
    {
        return [JobDefinitionRepository::class];
    }
    
    public function register()
    {
        $this->app->singleton(JobDefinitionRepository::class, JobDefinitionConfigRepository::class);
    }
}
