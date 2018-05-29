<?php

namespace App\Lib\InteractiveJobs;

use App\Lib\InteractiveJobs\Contracts\Declinable;
use App\Lib\InteractiveJobs\Contracts\Reportable;
use App\Lib\InteractiveJobs\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use function collect;
use function is_null;
use Log;

class InteractiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    protected $jobModel;
    protected $report;
    
    public function __construct(Job $jobModel)
    {
        $this->jobModel = $jobModel;
        $this->report = collect([]);
        if($this->jobModel->attempts > 1){
            $this->delay = 0;
        }
    }
    
    public function handle()
    {
        $this->registerContext();
        $this->beforeStart();
        $this->ensureNotDecline();
        try {
            app()->call([$this, 'execute']);
            if ($this instanceof Reportable) {
                $this->jobModel->report = $this->report;
            }
            $this->onFinish();
        } catch (\Throwable $e) {
            $this->onFail($e);
        } finally {
            $this->detachContext();
        }
       
    }
    
    protected function ensureNotDecline()
    {
        if ($this instanceof Declinable) {
            $this->jobModel->refresh();
            if ($this->jobModel->jobState()->isDeclined()) {
                $this->onDecline();
                return false;
            }
        }
        return true;
    }
    
    protected function context()
    {
        return $this->jobModel;
    }
    
    protected function registerContext()
    {
        Config::set('jobs.context', $this->context());
    }
    
    protected function detachContext()
    {
        Config::set('jobs.context', null);
    }
    
    protected function beforeStart()
    {
        $this->jobModel->activate();
    }
    
    protected function onFinish()
    {
        $this->jobModel->finish(true);
    }
    
    protected function onFail(\Throwable $e)
    {
        if ($this instanceof Reportable) {
            $this->jobModel->report = collect(['error' => $e->getMessage()]);
        }
        if ($this->isWillBeRetry()) {
            $this->jobModel->retryAfterFail();
        } else {
            $this->jobModel->finish(false);
        }
        $this->fail($e);
    }
    
    protected function isWillBeRetry(): bool
    {
        Log::info( 'retry info', [
            'max tries'=>$this->job->maxTries(),
            'attempts'=>$this->attempts(),
            'isRetried'=>$this->isRetried(),
            'isMarkFailed'=>$this->job->release()
        ]);
        return is_null($this->job->maxTries()) || $this->attempts() < $this->job->maxTries();
    }
    
    protected function isRetried(): bool
    {
        return  $this->jobModel->attempts > 1;
    }
    
}
