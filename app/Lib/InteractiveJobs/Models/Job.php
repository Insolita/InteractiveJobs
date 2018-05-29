<?php

namespace App\Lib\InteractiveJobs\Models;

use App\Lib\InteractiveJobs\JobDefinition;
use App\Lib\InteractiveJobs\JobState;
use App\Lib\InteractiveJobs\Notifications\LogMessage;
use App\Lib\InteractiveJobs\Notifications\Progress;
use App\Lib\InteractiveJobs\Notifications\StateMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property int                                                                     $id
 * @property string                                                                  $queue
 * @property string                                                                  $command
 * @property array|null                                                              $payload
 * @property array|null                                                              $report
 * @property string                                                                  $state
 * @property int|null                                                                $progress
 * @property int                                                                     $attempts
 * @property int                                                                     $created_by
 * @property \Illuminate\Support\Carbon                                              $created_at
 * @property \Illuminate\Support\Carbon                                              $finished_at
 * @property \App\User                                                               $owner
 * @property \App\Lib\InteractiveJobs\Models\JobLog[]|\Illuminate\Support\Collection $logs
 */
class Job extends Model
{
    use Notifiable;
    
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';
    
    public $timestamps = true;
    
    public $definition;
    
    protected $fillable = ['command', 'queue', 'payload', 'created_by'];
    
    protected $casts
        = [
            'payload' => 'array',
            'report' => 'array',
            'finished_at' => 'datetime',
        ];
    
    public function logs()
    {
        return $this->morphMany(JobLog::class, 'loggable');
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function scopeNotFinished(Builder $query)
    {
        return $query->whereNotIn('state', ['fail', 'success']);
    }
    
    public function setDefinition(JobDefinition $definition)
    {
        $this->definition = $definition;
        return $this;
    }
    
    public function identity()
    {
        return class_basename($this->command) . ':' . $this->id;
    }
    
    public function commandName()
    {
        return class_basename($this->command);
    }
    
    public function jobState(): JobState
    {
        return new JobState($this->state ?? JobState::PENDING);
    }
    
    public function receivesBroadcastNotificationsOn()
    {
        return 'Job.' . $this->id;
    }
    
    public function fillDefaults()
    {
        $this->progress = 0;
        $this->attempts = 0;
        $this->state = JobState::PENDING;
        return $this;
    }
    
    public function updateProgress(int $value, $message = null)
    {
        if ($message) {
            $this->notifyNow(new LogMessage($message));
        }
        $this->progress = max($value, 100);
        $this->save();
        $this->notifyNow(new Progress($value));
    }
    
    public function activate()
    {
        $this->state = JobState::PROCESSING;
        $this->save();
        $this->stateChanged();
    }
    
    public function retryAfterFail()
    {
        $this->state = JobState::RETRY;
        $this->attempts += 1;
        $this->save();
        $this->stateChanged();
    }
    
    public function finish(bool $success = true)
    {
        $this->state = $success ? JobState::SUCCESS : JobState::FAIL;
        $this->finished_at = Carbon::now();
        $this->save();
        $this->stateChanged();
    }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->commandName(),
            'command' => $this->command,
            'title' => optional($this->definition)->title(),
            'state' => $this->state,
            'payload' => $this->payload,
            'progress' => $this->progress,
            'report' => $this->report,
            'attempts' => $this->attempts,
            'owner' => $this->created_by,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
    
    protected function stateChanged()
    {
        $this->notifyNow(new StateMessage($this->state));
        $this->owner->notifyNow($this->jobState()->notice($this->identity()));
    }
}
