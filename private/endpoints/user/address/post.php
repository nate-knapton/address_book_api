<?php

use Controllers\AddressController;
use Models\AddressModel;


if (empty($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    throw new \Exception("User ID is required and must be numeric", 400);
}

$userId = $_GET['user_id'] ?? null;

$addressData = $_POST;

// Validate required fields
$requiredFields = ['address_line_1', 'city', 'postcode', 'country'];
foreach ($requiredFields as $field) {
    if (!isset($addressData[$field]) || empty($addressData[$field])) {
        throw new \Exception("Required field '{$field}' is missing or empty", 400);
    }
}

$addressController = new AddressController();

$createdAddress = $addressController->addAddressToUser((int)$userId, $addressData);
$addressModel = AddressModel::fromArray($createdAddress);

echo json_encode([
    'user_id' => (int)$userId,
    'data' => $addressModel->toArray(),
]);
