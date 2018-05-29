<?php

namespace App\Lib\InteractiveJobs;

use Illuminate\Http\Request;
use function call_user_func;
use function class_basename;

class JobDefinition
{
    /**
     * @var string
     */
    private $command;
    
    /**
     * @var array
     */
    private $rules = [];
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $title = '';
    
    /**
     * @var string
     */
    private $formView;
    
    /**
     * @var string
     */
    private $queue = 'default';
    
    /**
     * @var null|callable
     */
    private $customHandler;
    
    public function __construct(string $command, string $formView)
    {
        $this->command = $command;
        $this->formView = $formView;
        $this->title = $this->name = class_basename($command);
    }
    
    public function command(): string
    {
        return $this->command;
    }
    
    public function rules(): array
    {
        return $this->rules;
    }
    
    public function name(): string
    {
        return $this->name;
    }
    
    public function title(): string
    {
        return $this->title;
    }
    
    public function formView(): string
    {
        return $this->formView;
    }
    
    public function queue(): string
    {
        return $this->queue;
    }
    
    /**
     * @param array $rules Validation Rules
     *
     * @return JobDefinition
     */
    public function setRules(array $rules): JobDefinition
    {
        $this->rules = $rules;
        return $this;
    }
    
    /**
     * @param string $title
     *
     * @return JobDefinition
     */
    public function setTitle(string $title): JobDefinition
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * @param string $queue
     *
     * @return JobDefinition
     */
    public function onQueue(string $queue): JobDefinition
    {
        $this->queue = $queue;
        return $this;
    }
    
    /**
     * @param callable|null $handler
     *
     * @return JobDefinition
     */
    public function setCustomHandler(?callable $handler): JobDefinition
    {
        $this->customHandler = $handler;
        return $this;
    }
    
    public function handlePayload(Request $request):array
    {
        if ($this->customHandler) {
            return call_user_func($this->customHandler, $request);
        }else{
            return $request->except(['_token', 'confirm']);
        }
    }
    
    public static function create(string $command, string $formView = 'jobs.forms.confirm'): JobDefinition
    {
        return new static($command, $formView);
    }
}
