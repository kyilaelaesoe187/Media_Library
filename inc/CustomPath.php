<?php
spl_autoload_register(function ($class) {

    $baseDir = dirname(__DIR__) . '/';

    $paths = [
        'App/Contract/',
        'Controller/',
        'App/Service/',
        'App/Repository/',
        'Controller/',
        'view/',           // ItemView lives here
        'inc/',             // Database, helpers if class-based
    ];

    foreach ($paths as $path) {
        $file = $baseDir . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
