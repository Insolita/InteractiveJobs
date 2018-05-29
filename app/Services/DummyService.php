<?php

namespace App\Services;

use function call_user_func;
use Exception;
use Illuminate\Support\Carbon;
use Psr\Log\LoggerInterface;
use function sleep;

class DummyService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    
    private $callback;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Simulate complex logic with element of surprise
     *
     * @param int $loop
     * @param int $delay
     *
     * @throws \Exception
     */
    public function dummyJobLogic(int $loop = 10, int $delay = 1)
    {
        for ($i = 0; $i < $loop; $i++) {
            $this->logger->info('Iteration #' . $i . '/' . $loop);
            sleep($delay);
            if($this->callback){
                call_user_func($this->callback, $i, $loop);
            }
            if (Carbon::now()->timestamp % 10 === 0) {
                throw new Exception('Ooops! Fail');
            }
        }
        $this->logger->info(__METHOD__ . ' complete');
    }
    
    /**
     * @param mixed $callback
     *
     * @return DummyService
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
}
}
