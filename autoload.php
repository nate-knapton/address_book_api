<?php

/**
 * PSR-4 Autoloader for Address Book API
 * 
 * This autoloader dynamically loads classes based on their namespace and follows PSR-4 standards.
 * It maps namespaces to directories and automatically includes the required class files.
 */

spl_autoload_register(function ($className) {
    // Define the base directory for the project
    $baseDir = __DIR__ . '/private/';

    // Define namespace to directory mappings
    $namespaceMappings = [
        'Models\\' => 'models/',
        'Controllers\\' => 'controllers/',
    ];

    // Normalize the class name (remove leading backslashes)
    $className = ltrim($className, '\\');

    // Check each namespace mapping
    foreach ($namespaceMappings as $namespace => $directory) {
        // If the class name starts with this namespace
        if (strpos($className, $namespace) === 0) {
            // Remove the namespace prefix to get the relative class name
            $relativeClassName = substr($className, strlen($namespace));

            // Convert namespace separators to directory separators
            $relativeClassName = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassName);

            // Build the full file path
            $filePath = $baseDir . $directory . $relativeClassName . '.php';

            // If the file exists, include it
            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            }
        }
    }

    // If no mapping found, try a fallback approach
    // This handles cases where classes might not follow exact namespace conventions
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

/**
 * Enhanced autoloader that scans directories dynamically
 * This function discovers all available classes in the project
 */
function getAvailableClasses(): array
{
    $classes = [];
    $baseDir = __DIR__ . '/private/';

    $directories = [
        'models' => 'Models\\',
        'controllers' => 'Controllers\\'
    ];

    foreach ($directories as $dir => $namespace) {
        $fullDir = $baseDir . $dir;
        if (is_dir($fullDir)) {
            $files = scandir($fullDir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $className = pathinfo($file, PATHINFO_FILENAME);
                    $fullClassName = $namespace . $className;
                    $classes[] = $fullClassName;
                }
            }
        }
    }

    return $classes;
}

/**
 * Function to validate if a class exists and can be autoloaded
 */
function validateClass(string $className): bool
{
    try {
        return class_exists($className);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Debug function to show autoloader status
 */
function showAutoloaderStatus(): array
{
    return [
        'registered_autoloaders' => spl_autoload_functions(),
        'available_classes' => getAvailableClasses(),
        'base_directory' => __DIR__ . '/private/',
        'status' => 'Autoloader successfully registered'
    ];
}
