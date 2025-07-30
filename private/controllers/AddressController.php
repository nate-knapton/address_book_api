<?php

namespace Controllers;

use Models\AddressModel;

class AddressController extends DataController
{
    /**
     * Get addresses for a specific user ID
     */
    public function getAddressesByUserId(int $userId): array
    {
        $users = $this->getData();

        foreach ($users as $user) {
            if (isset($user['id']) && (int)$user['id'] === $userId) {
                // Return addresses if they exist, otherwise empty array
                return $user['addresses'] ?? [];
            }
        }

        // User not found
        throw new \Exception("User with ID {$userId} not found", 404);
    }

    /**
     * Add address to a specific user
     */
    public function addAddressToUser(int $userId, array $addressData): array
    {
        $users = $this->getData();
        $userFound = false;

        for ($i = 0; $i < count($users); $i++) {
            if (isset($users[$i]['id']) && (int)$users[$i]['id'] === $userId) {
                if (!isset($users[$i]['addresses'])) {
                    $users[$i]['addresses'] = [];
                }

                // Generate address ID within user's addresses
                $maxAddressId = 0;
                foreach ($users[$i]['addresses'] as $address) {
                    if (isset($address['id']) && $address['id'] > $maxAddressId) {
                        $maxAddressId = $address['id'];
                    }
                }

                $addressData['id'] = $maxAddressId + 1;
                $users[$i]['addresses'][] = $addressData;
                $userFound = true;
                break;
            }
        }

        if (!$userFound) {
            throw new \Exception("User with ID {$userId} not found", 404);
        }

        $this->saveData($users);
        return $addressData;
    }

    /**
     * Update a specific address for a user
     */
    public function updateUserAddress(int $userId, int $addressId, array $addressData): array
    {
        $users = $this->getData();
        $userFound = false;
        $addressFound = false;

        for ($i = 0; $i < count($users); $i++) {
            if (isset($users[$i]['id']) && (int)$users[$i]['id'] === $userId) {
                $userFound = true;

                if (isset($users[$i]['addresses'])) {
                    for ($j = 0; $j < count($users[$i]['addresses']); $j++) {
                        if (isset($users[$i]['addresses'][$j]['id']) && (int)$users[$i]['addresses'][$j]['id'] === $addressId) {
                            $addressData['id'] = $addressId;
                            $users[$i]['addresses'][$j] = $addressData;
                            $addressFound = true;
                            break;
                        }
                    }
                }
                break;
            }
        }

        if (!$userFound) {
            throw new \Exception("User with ID {$userId} not found", 404);
        }

        if (!$addressFound) {
            throw new \Exception("Address with ID {$addressId} not found for user {$userId}", 404);
        }

        $this->saveData($users);
        return $addressData;
    }

    /**
     * Delete a specific address for a user
     */
    public function deleteUserAddress(int $userId, int $addressId): bool
    {
        $users = $this->getData();
        $userFound = false;
        $addressFound = false;

        for ($i = 0; $i < count($users); $i++) {
            if (isset($users[$i]['id']) && (int)$users[$i]['id'] === $userId) {
                $userFound = true;

                if (isset($users[$i]['addresses'])) {
                    for ($j = 0; $j < count($users[$i]['addresses']); $j++) {
                        if (isset($users[$i]['addresses'][$j]['id']) && (int)$users[$i]['addresses'][$j]['id'] === $addressId) {
                            array_splice($users[$i]['addresses'], $j, 1);
                            $addressFound = true;
                            break;
                        }
                    }
                }
                break;
            }
        }

        if (!$userFound) {
            throw new \Exception("User with ID {$userId} not found", 404);
        }

        if (!$addressFound) {
            throw new \Exception("Address with ID {$addressId} not found for user {$userId}", 404);
        }

        $this->saveData($users);
        return true;
    }
}
