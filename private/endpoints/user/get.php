<?php

use Controllers\UserController;
use Models\UserModel;

$userController = new UserController();
$userData = $userController->getUsers();

$users = [];
if (is_array($userData)) {
    foreach ($userData as $userInfo) {
        if (is_array($userInfo)) {

            $UserModel = UserModel::fromArray($userInfo);
            $users[] = $UserModel->toArray();

            if (! empty($_GET['user_id'])) {
                if ($_GET['user_id'] == $UserModel->getId()) {
                    $users = [$UserModel->toArray()];
                    break;
                }
            }
        }
    }
}

echo json_encode([
    'success' => true,
    'data' => $users,
    'count' => count($users),
]);
