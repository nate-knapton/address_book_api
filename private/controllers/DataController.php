<?php

namespace Controllers;

class DataController
{
    private string $filePath;

    public function __construct()
    {
        // Use the dataset.json file in the same directory as this controller
        $this->filePath = __DIR__ . '/../dataset.json';
    }

    public function getData(): array
    {
        if (!file_exists($this->filePath)) {
            throw new \Exception("Data file not found at: " . $this->filePath, 500);
        }

        $datastream = file_get_contents($this->filePath);

        if ($datastream === false) {
            throw new \Exception("Unable to read data file", 500);
        }

        if (empty($datastream)) {
            return [];
        }

        $data = json_decode($datastream, true);

        if ($data === false) {
            throw new \Exception("Error decoding JSON data: " . json_last_error_msg(), 500);
        }

        return $data;
    }

    public function saveData(array $data): bool
    {

        if (!file_exists($this->filePath)) {
            throw new \Exception("Data file not found at: " . $this->filePath, 500);
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        $write = file_put_contents($this->filePath, $jsonData);

        if ($write === false) {
            throw new \Exception("Error writing JSON data to file: " . $this->filePath, 500);
        }

        // Save data to the model or database
        return true;
    }
}
