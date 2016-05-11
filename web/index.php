<?php

require dirname(__DIR__) . '/system/autoload.php';



/*
//
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$run     = new Run();
$handler = new PrettyPageHandler();

$run->pushHandler($handler);
// Example: tag all frames inside a function with their function name
$run->pushHandler(function ($exception, $inspector, $run) {
    $inspector->getFrames()->map(function ($frame) {
        if ($function = $frame->getFunction()) {
            $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
        }
        return $frame;
    });
});
$run->register();
*/



//
// require dirname(__DIR__) . '/application/boot.php';
// require dirname(__DIR__) . '/application.viewd/boot.php';
require dirname(__DIR__) . '/application.aura.router/boot.php';

// eof
