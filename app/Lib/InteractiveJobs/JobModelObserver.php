<?php

namespace App\Lib\InteractiveJobs;

use App\Lib\InteractiveJobs\Models\Job;
use App\Lib\InteractiveJobs\Notifications\JobCreatedMessage;
use function dispatch;

class JobModelObserver
{
    public function creating(Job $job)
    {
        $job->fillDefaults();
    }
    
    public function created(Job $job)
    {
        try{
            $command = app()->make($job->command, ['jobModel'=>$job]);
            dispatch($command)->onQueue($job->queue)->delay(2);
            $job->notifyNow(new JobCreatedMessage());
        }catch (\Throwable $e){
            $job->delete();
        }
    }
}
