<?php

namespace App\Lib\InteractiveJobs\Contracts;

use App\Lib\InteractiveJobs\JobDefinition;
use Illuminate\Support\Collection;

interface JobDefinitionRepository
{
    /**
     * @return Collection|\App\Lib\InteractiveJobs\JobDefinition[]
     */
    public function jobDefinitions(): Collection;
    
    public function findByName($jobName): ?JobDefinition;
    
    public function exists($jobName): bool;
}
