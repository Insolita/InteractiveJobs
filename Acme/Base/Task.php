<?php

namespace Acme\Base;

class Task
{
    protected static $stepMap=[];
    public $identity;
    public $payload;
    public $owner;
    public $executionState;
    public $resultState;
    public $step;
    public $stepPayload;
}
