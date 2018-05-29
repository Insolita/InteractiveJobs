<?php
use App\Jobs\DummyJob;
use App\Jobs\UrlChecker;
use App\Jobs\UrlContentChecker;
use App\Lib\InteractiveJobs\JobDefinition;

return [
    'context' => null,
    'definitions' => [
        JobDefinition::create(DummyJob::class, 'jobs.forms.dummy')
            ->setTitle('Simple example job')
            ->setRules([
                'delay' => 'integer|min:1|max:5',
                'loop' => 'integer|min:1|max:5',
            ]),
        JobDefinition::create(UrlChecker::class, 'jobs.forms.url')
            ->setTitle('Simple url checker')
            ->setRules([
                'url' => 'url',
            ]),
        JobDefinition::create(UrlContentChecker::class, 'jobs.forms.url')
            ->setTitle('Simple url content checker')
            ->setRules([
                'url' => 'url',
            ]),
    ],
];

