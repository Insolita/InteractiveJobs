<?php

namespace App\Lib\InteractiveJobs\Logger;

use App\Lib\InteractiveJobs\Entity\JobContext;
use App\Lib\InteractiveJobs\Models\Job;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use Monolog\Handler\AbstractProcessingHandler;

class JobLogDbHandler extends AbstractProcessingHandler
{
    
    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        
        $jobContext = Config::get('jobs.context');
        if($jobContext instanceof Model){
            DB::table('job_logs')->insert([
                'loggable_id'=>$jobContext->id,
                'loggable_type'=>$jobContext->commandName(),
                'message' => $record['message'],
                'level' => $record['level_name'],
                'context' => json_encode($record['context'] ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
                'extra' => json_encode($record['extra']??[], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
                'created_at' => $record['datetime']->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
