<?php

spl_autoload_register(function ($className) {

    $baseDir = __DIR__ . '/private/';

    $namespaceMappings = [
        'Models\\' => 'models/',
        'Controllers\\' => 'controllers/',
    ];

    $className = ltrim($className, '\\');

    foreach ($namespaceMappings as $namespace => $directory) {

        if (strpos($className, $namespace) === 0) {

            $relativeClassName = substr($className, strlen($namespace));

            $relativeClassName = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassName);

            $filePath = $baseDir . $directory . $relativeClassName . '.php';

            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            }
        }
    }

    $fallbackMappings = [
        'Model' => 'models/',
        'Controller' => 'controllers/',
    ];

    foreach ($fallbackMappings as $suffix => $directory) {
        if (str_ends_with($className, $suffix)) {
            $filePath = $baseDir . $directory . $className . '.php';
            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            }
        }
    }

    return false;
});
