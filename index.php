<?php

// Include the autoloader
require_once __DIR__ . '/autoload.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Get the request URI and remove the base path
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/address_book_api';
$requestUri = str_replace($basePath, '', $requestUri);

// Remove query string if present
$requestUri = strtok($requestUri, '?');

// Remove leading and trailing slashes
$requestUri = trim($requestUri, '/');

// Split the URI into parts
$uriParts = explode('/', $requestUri);

// Get HTTP method
$method = strtolower($_SERVER['REQUEST_METHOD']);

try {
    // Route the request
    if (count($uriParts) >= 2) {
        $resource = $uriParts[0]; // category
        $action = $uriParts[1];   // endpoint or ID

        // Special handling for users/{id}/addresses pattern
        if ($resource === 'users' && count($uriParts) === 3 && $uriParts[2] === 'addresses') {
            // Handle users/{id}/addresses
            $userId = $action; // The second part is the user ID
            if (!is_numeric($userId)) {
                throw new \Exception("Invalid user ID: {$userId}. Must be numeric.", 400);
            }

            // Set parameters for the addresses endpoint
            $_GET['user_id'] = (int)$userId;

            // Map HTTP method to file for addresses
            $methodToAddressFile = [
                'get' => 'get.php',
                'post' => 'post.php'
            ];

            if (isset($methodToAddressFile[$method])) {
                $endpointPath = __DIR__ . "/private/endpoints/addresses/{$methodToAddressFile[$method]}";
                if (file_exists($endpointPath)) {
                    include $endpointPath;
                } else {
                    throw new \Exception("Address endpoint not found: {$endpointPath}", 500);
                }
            } else {
                throw new \Exception("Method '{$method}' not allowed for /users/{id}/addresses", 405);
            }
            return; // Exit early since we handled this special case
        }

        // Special handling for users/{id}/addresses/{addressId} pattern (4 parts)
        if ($resource === 'users' && count($uriParts) === 4 && $uriParts[2] === 'addresses') {
            $userId = $action; // The second part is the user ID
            $addressId = $uriParts[3]; // The fourth part is the address ID

            if (!is_numeric($userId)) {
                throw new \Exception("Invalid user ID: {$userId}. Must be numeric.", 400);
            }

            if (!is_numeric($addressId)) {
                throw new \Exception("Invalid address ID: {$addressId}. Must be numeric.", 400);
            }

            // Set parameters for the addresses endpoint
            $_GET['user_id'] = (int)$userId;
            $_GET['address_id'] = (int)$addressId;

            // Map HTTP method to file
            $methodToFile = [
                'get' => 'get.php',
                'put' => 'put.php',
                'delete' => 'delete.php'
            ];

            if (isset($methodToFile[$method])) {
                $endpointPath = __DIR__ . "/private/endpoints/addresses/{$methodToFile[$method]}";
                if (file_exists($endpointPath)) {
                    include $endpointPath;
                } else {
                    throw new \Exception("Address endpoint not found: {$endpointPath}", 500);
                }
            } else {
                throw new \Exception("Method '{$method}' not allowed for address operations", 405);
            }
            return; // Exit early since we handled this special case
        }

        // Dynamically discover available resources for normal routing
        $endpointsDir = __DIR__ . '/private/endpoints/';
        $availableResources = [];

        if (is_dir($endpointsDir)) {
            $resourceDirs = scandir($endpointsDir);
            foreach ($resourceDirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($endpointsDir . $dir)) {
                    $availableResources[] = $dir;
                }
            }
        }

        // Security: Validate resource against discovered resources
        // This prevents directory traversal attacks
        if (!in_array($resource, $availableResources)) {
            throw new \Exception("Resource '{$resource}' not found. Available resources: " . implode(', ', $availableResources), 404);
        }

        // Security: Validate action to prevent directory traversal
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $action)) {
            throw new \Exception("Invalid action format: '{$action}'. Only alphanumeric characters, underscores, and hyphens are allowed.", 400);
        }

        // Map HTTP methods to file names
        $methodToFile = [
            'get' => 'get.php',
            'post' => 'post.php',
            'put' => 'put.php',
            'delete' => 'delete.php'
        ];

        // Determine the file to include based on HTTP method or action
        $file = null;

        // If action matches a method file, use that
        if (isset($methodToFile[$action])) {
            $file = $methodToFile[$action];
        }
        // Otherwise, map HTTP method to file
        elseif (isset($methodToFile[$method])) {
            $file = $methodToFile[$method];
            // Add the action as a parameter for the endpoint to use
            $_GET['action'] = $action;
        }

        if ($file) {
            $endpointPath = __DIR__ . "/private/endpoints/{$resource}/{$file}";

            if (file_exists($endpointPath)) {
                // Include the endpoint file
                include $endpointPath;
            } else {
                throw new \Exception("Endpoint file not found: {$endpointPath}", 404);
            }
        } else {
            throw new \Exception("Method '{$method}' not allowed for action '{$action}'", 405);
        }
    } else {
        // Default route - API information with dynamic endpoint discovery
        $endpointsDir = __DIR__ . '/private/endpoints/';
        $discoveredEndpoints = [];

        if (is_dir($endpointsDir)) {
            $resourceDirs = scandir($endpointsDir);
            foreach ($resourceDirs as $resourceDir) {
                if ($resourceDir !== '.' && $resourceDir !== '..' && is_dir($endpointsDir . $resourceDir)) {
                    $resourcePath = $endpointsDir . $resourceDir;
                    $endpointFiles = scandir($resourcePath);

                    $discoveredEndpoints[$resourceDir] = [];
                    foreach ($endpointFiles as $file) {
                        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                            $method = strtoupper(pathinfo($file, PATHINFO_FILENAME));
                            $endpoint = pathinfo($file, PATHINFO_FILENAME);
                            $discoveredEndpoints[$resourceDir][] = [
                                'method' => $method,
                                'endpoint' => "/{$resourceDir}/{$endpoint}",
                                'description' => ucfirst($method) . " " . ucfirst($resourceDir)
                            ];
                        }
                    }
                }
            }
        }

        echo json_encode([
            'message' => 'Address Book API',
            'version' => '1.0.0',
            'autoloader' => [
                'status' => 'Active',
                'available_classes' => getAvailableClasses()
            ],
            'endpoints' => $discoveredEndpoints,
            'usage' => [
                'base_url' => $basePath,
                'format' => '/{resource}/{action}',
                'example' => '/users/get'
            ]
        ]);
    }
} catch (Exception $e) {
    // Centralized exception handling for consistent error formatting
    $errorResponse = [
        'success' => false,
        'error' => [
            'message' => $e->getMessage(),
            'type' => get_class($e),
            'timestamp' => date('Y-m-d H:i:s'),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ]
    ];

    // Use exception code as HTTP status code, with fallback logic
    $statusCode = $e->getCode();

    // If no code provided or invalid code, determine from message content
    if ($statusCode < 100 || $statusCode > 599) {
        if (
            strpos($e->getMessage(), 'not found') !== false ||
            strpos(strtolower($e->getMessage()), 'file') !== false
        ) {
            $statusCode = 404;
        } elseif (
            strpos($e->getMessage(), 'invalid') !== false ||
            strpos(strtolower($e->getMessage()), 'validation') !== false ||
            strpos(strtolower($e->getMessage()), 'required') !== false
        ) {
            $statusCode = 400;
        } elseif (
            strpos(strtolower($e->getMessage()), 'unauthorized') !== false ||
            strpos(strtolower($e->getMessage()), 'permission') !== false
        ) {
            $statusCode = 403;
        } else {
            $statusCode = 500; // Default to Internal Server Error
        }
    }

    // Add development details if in development mode
    $isDebugMode = (defined('APP_DEBUG') && constant('APP_DEBUG') === true) ||
        (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true');

    if ($isDebugMode) {
        $errorResponse['debug'] = [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ];
    }

    http_response_code($statusCode);
    echo json_encode($errorResponse);
}
