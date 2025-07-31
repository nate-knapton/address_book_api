<?php

use Controllers\UserController;
use Models\UserModel;

$userController = new UserController();
$userData = $userController->getUsers();

if (empty($_POST['email'])) {
    throw new \Exception("Email is required", 400);
}

$ActiveUser = null;

if (is_array($userData)) {
    foreach ($userData as $userInfo) {
        if (is_array($userInfo)) {

            $UserModel = UserModel::fromArray($userInfo);

            if ($_POST['email'] === $UserModel->getEmail()) {
                $ActiveUser = $UserModel;
                break;
            }
        }
    }
}

if (!$ActiveUser) {
    throw new \Exception("User account not found", 404);
}

echo json_encode([
    'success' => true,
    'data' => $ActiveUser ? $ActiveUser->toArray() : null,
]);
