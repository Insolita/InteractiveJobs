<?php

namespace App\Lib\InteractiveJobs\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class Progress extends Notification
{
    /**
     * @var int
     */
    private $progress;
    
    public function __construct(int $progress = 1)
    {
        $this->progress = $progress;
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
            'message_type' => Notificator::EVENT_PROGRESS,
            'message' => $this->progress,
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
        return 'JobProgress';
    }
}
