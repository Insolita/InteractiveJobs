<?php

namespace App\Jobs;

use App\Lib\InteractiveJobs\InteractiveJob;
use App\Lib\InteractiveJobs\Notifications\LogMessage;
use App\Lib\InteractiveJobs\Notifications\Progress;
use App\Services\DummyService;
use function array_random;
use function usleep;

class DummyJob extends InteractiveJob
{
    /**
     * Execute the job.
     *
     * @param \App\Services\DummyService $service
     *
     * @return void
     */
    public function execute(DummyService $service)
    {
        $payload = $this->jobModel->payload;
        $service->setCallback(function ($i, $loop) {
            $ratio = round($i*100/$loop);
            $this->jobModel->notifyNow(new Progress($ratio));
            usleep(300);
            $this->jobModel->notifyNow(new LogMessage(array_random(['apple', 'banana','orange','melon']).' job message'));
        })->dummyJobLogic($payload['loop'] ?? 3, $payload['delay'] ?? 1);
    }
}
