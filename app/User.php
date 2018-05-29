<?php

namespace App;

use App\Lib\InteractiveJobs\Models\Job;
use App\Lib\InteractiveJobs\Models\JobLog;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 */
class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'email',
            'password',
        ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];
    
    public function logs()
    {
        return $this->morphMany(JobLog::class, 'loggable');
    }
    
    public function jobs()
    {
        return $this->hasMany(Job::class, 'created_by');
    }
    
    public function hasJob($jobId):bool
    {
        return $this->jobs()->where('id', $jobId)->exists();
    }
    
    public function isAdmin()
    {
        return $this->name === 'admin';
    }
    
    public function toCredentials()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->isAdmin() ? 'admin' : 'user',
        ];
    }
    
    public function receivesBroadcastNotificationsOn()
    {
        return 'User.' . $this->id;
    }
}
