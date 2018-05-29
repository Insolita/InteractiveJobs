@php
    /**
    * @var \App\Lib\InteractiveJobs\JobDefinition[] $definitions
    */
@endphp


<div class="card">
    <div class="card-header">Jobs</div>
    <div class="list-group list-group-flush">
        @foreach($definitions as $definition)
            <a class="list-group-item list-group-item-action @if(Route::input('job') == $definition->name()) active
@endif"
               href="{{route('jobs.create', ['job'=>$definition->name()])}}">
                {{$definition->title()}}
            </a>
        @endforeach
    </div>
</div>



