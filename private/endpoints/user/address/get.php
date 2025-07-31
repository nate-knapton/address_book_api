<?php

use Controllers\AddressController;
use Models\AddressModel;


if (empty($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    throw new \Exception("User ID is required and must be numeric", 400);
}

$userId = $_GET['user_id'] ?? null;

$addressController = new AddressController();

// Get addresses for specific user
$addressesData = $addressController->getAddressesByUserId((int)$userId);

// Convert to AddressModel objects if data exists
$addresses = [];
if (is_array($addressesData)) {
    foreach ($addressesData as $addressInfo) {
        if (is_array($addressInfo)) {
            $address = AddressModel::fromArray($addressInfo);
            $addresses[] = $address->toArray();
        }
    }
}

echo json_encode([
    'user_id' => (int)$userId,
    'data' => $addresses,
    'count' => count($addresses),
    'message' => count($addresses) > 0 ? 'Addresses found' : 'No addresses found for this user'
]);
