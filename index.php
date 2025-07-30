<?php

require_once __DIR__ . '/autoload.php';

//Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Get the request URI and strip out base url/query params etc.
$requestUri = $_SERVER['REQUEST_URI'];
$baseUri = '/address_book_api';
$requestUri = str_replace($baseUri, '', $requestUri);
$requestUri = trim(explode('?', $requestUri)[0]);
$requestUri = trim($requestUri, '/');


// Split into parts
$uriParts = explode('/', $requestUri);

try {

    $category = $uriParts[0]; // category
    $requestType = $_SERVER['REQUEST_METHOD'];
    $action = $uriParts[1];   // endpoint or ID

    $acceptedMethods = ['get', 'post', 'put', 'delete'];

    if (! in_array(strtolower($requestType), $acceptedMethods)) {
        throw new \Exception("Unsupported request method: {$requestType}", 405);
    }

    //Check for ids, set $_GET parameters
    foreach ($uriParts as $index => $uriPart) {
        if (is_numeric($uriPart)) {
            $_GET[$uriParts[$index - 1] . '_id'] = (int)$uriPart; // e.g. user_id, address_id
            unset($uriParts[$index]); //Strip out leaving the path
        }
    }

    $uriParts = array_values($uriParts); // Re-index the array after unset

    $endpointPath =  "./private/endpoints/" . implode('/', $uriParts) . '/' . strtolower($requestType) . '.php';

    if (file_exists($endpointPath)) {
        // Include the endpoint file
        include $endpointPath;
    } else {
        throw new \Exception("Endpoint file not found: {$endpointPath}", 404);
    }
} catch (Exception $Exception) {

    //Error response
    http_response_code($Exception->getCode() ?: 500);
    $errorResponse = [
        'success' => false,
        'error' => $Exception->getMessage()
    ];

    die(json_encode($errorResponse));
}
