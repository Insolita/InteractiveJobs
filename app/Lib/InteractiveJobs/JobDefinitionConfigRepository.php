<?php

namespace App\Lib\InteractiveJobs;

use App\Lib\InteractiveJobs\Contracts\JobDefinitionRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class JobDefinitionConfigRepository implements JobDefinitionRepository
{
    private $jobDefinitions;
    /**
     * @return Collection|\App\Lib\InteractiveJobs\JobDefinition[]
     */
    public function jobDefinitions(): Collection
    {
        if (!$this->jobDefinitions) {
            $this->jobDefinitions = collect(Config::get('jobs.definitions', []))->keyBy->name();
        }
        return $this->jobDefinitions;
    }
    
    public function findByName($jobName): ?JobDefinition
    {
        return $this->jobDefinitions()->get($jobName);
    }
    
    public function exists($jobName): bool
    {
        return $this->jobDefinitions()->has($jobName);
    }
}
