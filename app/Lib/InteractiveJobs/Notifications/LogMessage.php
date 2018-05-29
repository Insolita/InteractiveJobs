<?php

namespace App\Lib\InteractiveJobs\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LogMessage extends Notification
{
    private $message;
    
    public function __construct(string $message)
    {
        $this->message = $message;
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
            'message_type' => Notificator::EVENT_LOG_MESSAGE,
            'message' => $this->message,
            'id' => $notifiable->id,
        ];
    }
    
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage($this->toArray($notifiable)))->onQueue('notify')->delay(0);
    }
    
    public function broadcastAs()
    {
        return 'jobMessage';
    }
}
