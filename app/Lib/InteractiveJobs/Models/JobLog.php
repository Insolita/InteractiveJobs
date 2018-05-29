<?php

namespace App\Lib\InteractiveJobs\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                        $id
 * @property int                        $loggable_id
 * @property int                        $loggable_type
 * @property string                     $level
 * @property string                     $message
 * @property string                     $context
 * @property string                     $extra
 * @property \Illuminate\Support\Carbon $created_at
 * @method static|Builder groups()
 * @method static|Builder job(JobLog $jobLog)
 */
class JobLog extends Model
{
    const UPDATED_AT = null;
    
    public $timestamps = true;
    
    protected $table = 'job_logs';
    
    public function scopeGroups(Builder $query)
    {
        return $query->groupBy('loggable_type', 'loggable_id')
            ->selectRaw('MAX(id) as id, MAX(created_at) as created_at, COUNT(id) as total')
            ->addSelect(['loggable_id', 'loggable_type'])
            ->orderByRaw('MAX(created_at) DESC');
    }
    
    public function scopeJob(Builder $query, JobLog $jobLog)
    {
        return $query->where('loggable_id', $jobLog->loggable_id)
            ->where('loggable_type', $jobLog->loggable_type)
            ->orderByDesc('created_at');
    }
}
