<?php

namespace App\Jobs;

use App\Lib\InteractiveJobs\Contracts\Reportable;
use App\Lib\InteractiveJobs\InteractiveJob;
use Exception;
use function file_get_contents;
use Illuminate\Support\Facades\Log;

class UrlChecker extends InteractiveJob implements Reportable
{
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function execute()
    {
        $payload = $this->jobModel->payload;
        $url = $payload['url'];
        try{
            $page = file_get_contents($url);
            Log::info('url = '.$url);
            if($page){
               $this->report['loadable'] = true;
            }else{
                $this->report['loadable'] = false;
            }
        }catch (\Exception $e){
            $this->report['loadable'] = false;
        }
        throw new Exception('Force fail exception');
    }
    
}
