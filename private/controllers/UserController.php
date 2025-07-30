<?php

namespace Controllers;

class UserController extends DataController
{
    public function getUsers(): array
    {
        $users = $this->getData();
        return $users;
    }

    public function getUserById(int $id): array
    {
        $users = $this->getData();

        foreach ($users as $user) {
            if (isset($user['id']) && (int)$user['id'] === $id) {
                return $user;
            }
        }

        throw new \Exception("User with ID {$id} not found", 404);
    }

    public function createUser(array $userData): array
    {
        $users = $this->getData();

        // Generate new ID
        $maxId = 0;
        foreach ($users as $user) {
            if (isset($user['id']) && $user['id'] > $maxId) {
                $maxId = $user['id'];
            }
        }

        $userData['id'] = $maxId + 1;
        $userData['addresses'] = $userData['addresses'] ?? [];

        $users[] = $userData;
        $this->saveData($users);

        return $userData;
    }

    public function updateUser(int $id, array $userData): array
    {
        $users = $this->getData();
        $userFound = false;

        for ($i = 0; $i < count($users); $i++) {
            if (isset($users[$i]['id']) && (int)$users[$i]['id'] === $id) {
                // Preserve ID and merge data
                $userData['id'] = $id;
                $userData['addresses'] = $userData['addresses'] ?? $users[$i]['addresses'] ?? [];
                $users[$i] = $userData;
                $userFound = true;
                break;
            }
        }

        if (!$userFound) {
            throw new \Exception("User with ID {$id} not found", 404);
        }

        $this->saveData($users);
        return $userData;
    }

    public function deleteUser(int $id): bool
    {
        $users = $this->getData();
        $userFound = false;

        for ($i = 0; $i < count($users); $i++) {
            if (isset($users[$i]['id']) && (int)$users[$i]['id'] === $id) {
                array_splice($users, $i, 1);
                $userFound = true;
                break;
            }
        }

        if (!$userFound) {
            throw new \Exception("User with ID {$id} not found", 404);
        }

        $this->saveData($users);
        return true;
    }
}
