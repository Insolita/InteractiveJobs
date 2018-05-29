<?php

namespace App\Lib\InteractiveJobs;

use App\Lib\InteractiveJobs\Contracts\JobDefinitionRepository;
use Illuminate\View\View;

class JobMenuComposer
{
    /**
     * @var \App\Lib\InteractiveJobs\Contracts\JobDefinitionRepository
     */
    private $repository;
    
    public function __construct(JobDefinitionRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function compose(View $view)
    {
        $view->with('definitions', $this->repository->jobDefinitions()->toArray());
    }
}
