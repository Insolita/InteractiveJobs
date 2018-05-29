<?php

namespace App\Lib\InteractiveJobs\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use function optional;

class JobCreatedMessage extends Notification
{
    private $notifable;
    
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }
    
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'job' => $notifiable->toJson(),
        ];
    }
    
    public function toBroadcast($notifiable)
    {
        $this->notifable = $notifiable;
        return (new BroadcastMessage($this->toArray($notifiable)))->onQueue('notify')->delay(0);
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('JobsMonitor.' . optional($this->notifable)->created_by);
    }
    
    public function broadcastAs()
    {
        return 'JobCreated';
    }
}
