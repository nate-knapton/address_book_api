<?php

namespace Models;

abstract class BaseModel
{
    /**
     * Convert the model to an array using reflection to access private properties.
     */
    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();

        $result = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();

            // Convert camelCase to snake_case for JSON output
            $snakeName = $this->camelToSnake($propertyName);
            $result[$snakeName] = $property->getValue($this);
        }

        return $result;
    }

    /**
     * Populate model from an array using property setters or direct assignment.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        $instance = new static();
        $reflection = new \ReflectionClass($instance);

        foreach ($data as $key => $value) {
            // Convert snake_case to camelCase
            $camelKey = $instance->snakeToCamel($key);

            // Try to use setter method first
            $setterMethod = 'set' . ucfirst($camelKey);
            if (method_exists($instance, $setterMethod)) {
                $instance->$setterMethod($value);
                continue;
            }

            // Fallback to direct property assignment
            try {
                $property = $reflection->getProperty($camelKey);
                $property->setAccessible(true);
                $property->setValue($instance, $value);
            } catch (\ReflectionException $e) {
                // Property doesn't exist, skip it
                continue;
            }
        }

        return $instance;
    }

    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    private function snakeToCamel(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }
}
