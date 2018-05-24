<?php

namespace Acme\Values;

class TaskExecutionState
{
    const STATE_PENDING = 'pending';
    const STATE_PROCESSING = 'processing';
    const STATE_COMPLETE = 'complete';
    const STATE_WAIT_RETRY = 'wait_retry';
}
