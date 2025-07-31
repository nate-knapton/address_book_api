<?php

use Controllers\AddressController;
use Models\AddressModel;


if (empty($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    throw new \Exception("User ID is required and must be numeric", 400);
}

if (empty($_GET['address_id']) || !is_numeric($_GET['address_id'])) {
    throw new \Exception("Address ID is required and must be numeric", 400);
}

$userId = $_GET['user_id'] ?? null;
$addressId = $_GET['address_id'] ?? null;

$addressController = new AddressController();

// Update the address
$updatedAddress = $addressController->updateUserAddress((int)$userId, (int)$addressId, $_POST);

// Convert to AddressModel for consistent response
$addressModel = AddressModel::fromArray($updatedAddress);

echo json_encode([
    'user_id' => (int)$userId,
    'address_id' => (int)$addressId,
    'data' => $addressModel->toArray(),
]);
