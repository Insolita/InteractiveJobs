<?php

namespace App\Lib\InteractiveJobs\Notifications;

class Notificator
{
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'danger';
    const TYPE_SUCCESS = 'success';
    const TYPE_DEFAULT = 'default';
    const TYPE_WARNING = 'warning';
    
    const EVENT_PROGRESS = 'progress';
    const EVENT_LOG_MESSAGE = 'log_message';
    const EVENT_STATE = 'state';
}
