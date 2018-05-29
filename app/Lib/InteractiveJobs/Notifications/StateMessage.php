<?php

namespace App\Lib\InteractiveJobs\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class StateMessage extends Notification
{
    private $state;
    
    public function __construct(string $state)
    {
        $this->state = $state;
    }
    
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    
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
            'message_type' => Notificator::EVENT_STATE,
            'message' => $this->state,
            'job'=>$notifiable->toJson(),
            'id' => $notifiable->id,
        ];
    }
   
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage($this->toArray($notifiable)))->onQueue('notify')->delay(0);
    }
    
    public function broadcastAs()
    {
        return 'jobState';
    }
}
