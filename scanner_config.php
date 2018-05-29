<?php
$projectPath = __DIR__ ;
//Declare directories which contains php code
$scanDirectories = [
    $projectPath . '/app/',
    $projectPath . '/Acme/',
    $projectPath . '/resources/views/',
    $projectPath.'/routes/'
];
//Optionally declare standalone files
$scanFiles = [
    $projectPath . '/helpers.php',
];
return [
    'composerJsonPath' => $projectPath . '/composer.json',
    'vendorPath' => $projectPath . '/vendor/',
    'scanDirectories' => $scanDirectories,
    'scanFiles'=>$scanFiles
];