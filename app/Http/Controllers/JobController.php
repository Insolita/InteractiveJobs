<?php

namespace App\Http\Controllers;

use App\Lib\InteractiveJobs\Contracts\JobDefinitionRepository;
use App\Lib\InteractiveJobs\Models\Job;
use App\Lib\InteractiveJobs\Models\JobResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function abort_if;
use function class_basename;
use function compact;
use function redirect;
use function view;

class JobController extends Controller
{
    /**
     * @var \App\Lib\InteractiveJobs\Contracts\JobDefinitionRepository
     */
    protected $repository;
    
    public function __construct(JobDefinitionRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function index()
    {
        $jobs = Job::query()->latest('created_at')->paginate();
        return view('jobs.index', ['jobs' => $jobs]);
    }
    
    public function watch()
    {
        $jobs = Job::notFinished()->get()->toJson();
        return view('jobs.watch', ['jobs' => $jobs]);
    }
    
    public function create($job)
    {
        $definition = $this->repository->findByName($job);
        abort_if(!$definition, 404);
        return view('jobs.form', ['definition' => $definition]);
    }
    
    public function store(Request $request, $job)
    {
        $definition = $this->repository->findByName($job);
        abort_if(!$definition, 404);
        if (!empty($definition->rules())) {
            $this->validate($request, $definition->rules());
        }
        $jobModel = Job::create([
            'command' => $definition->command(),
            'queue' => $definition->queue(),
            'payload' => $definition->handlePayload($request),
            'created_by' => Auth::user()->id,
        ]);
        return redirect()->route('jobs.show', ['job' => $jobModel]);
    }
    
    public function show(Job $job)
    {
        $definition = $this->repository->findByName(class_basename($job->command));
        $job->setDefinition($definition);
        return view('jobs.show', compact('job', 'definition'));
    }
    
}
