<?php

namespace App\Lib\InteractiveJobs\Notifications;

use App\Lib\InteractiveJobs\Models\Job;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use function sprintf;

class UserMessage extends Notification
{
    
    private $type;
    
    private $message;
    
    public function __construct(string $type, string $message)
    {
        $this->type = $type;
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
        if ($notifiable instanceof Job) {
            $this->message = sprintf('[%s:%s] %s', $notifiable->command, $notifiable->id, $this->message);
        }
        return [
            'message_type' => $this->type,
            'message' => $this->message,
        ];
    }
    
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage($this->toArray($notifiable)))->onQueue('notify')->delay(0);
    }
    
    public function broadcastAs()
    {
        return 'UserNotice';
    }
}
