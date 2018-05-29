<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('PG_HOST', '127.0.0.1'),
            'port' => env('PG_PORT', '5432'),
            'database' => env('PG_DATABASE', 'forge'),
            'username' => env('PG_USERNAME', 'forge'),
            'password' => env('PG_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'test' => [
            'driver' => 'pgsql',
            'host' => env('PG_HOST', '127.0.0.1'),
            'port' => env('PG_PORT', '5432'),
            'database' => env('PG_TEST_DATABASE', 'forge'),
            'username' => env('PG_USERNAME', 'forge'),
            'password' => env('PG_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],
    'migrations' => 'migrations',
    'redis' => [
        'client' => 'predis',
        'options'=>[
           // 'prefix'=> 'taskapp:'
        ],
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 2
        ],
    ],

];
