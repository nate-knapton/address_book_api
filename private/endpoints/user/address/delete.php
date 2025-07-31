<?php

use Controllers\AddressController;

if (empty($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    throw new \Exception("User ID is required and must be numeric", 400);
}

if (empty($_GET['address_id']) || !is_numeric($_GET['address_id'])) {
    throw new \Exception("Address ID is required and must be numeric", 400);
}

$userId = $_GET['user_id'] ?? null;
$addressId = $_GET['address_id'] ?? null;

$addressController = new AddressController();

// Delete the address
$addressController->deleteUserAddress((int)$userId, (int)$addressId);

echo json_encode([
    'success' => true,
    'user_id' => (int)$userId,
    'address_id' => (int)$addressId,
]);
