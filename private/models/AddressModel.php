<?php

namespace Models;

class AddressModel extends BaseModel
{
    private string $addressLine1 = '';
    private string $addressLine2 = '';
    private string $addressLine3 = '';
    private string $city = '';
    private string $county = '';
    private string $postcode = '';
    private string $country = '';

    /**
     * Returns the first line of the address.
     * @return string
     */
    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }

    /**
     * Sets the first line of the address.
     * @param string $addressLine1
     */
    public function setAddressLine1(string $addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * Returns the second line of the address.
     * @return string
     */
    public function getAddressLine2(): string
    {
        return $this->addressLine2;
    }

    /**
     * Sets the second line of the address.
     * @param string $addressLine2
     */
    public function setAddressLine2(string $addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * Returns the third line of the address.
     * @return string
     */
    public function getAddressLine3(): string
    {
        return $this->addressLine3;
    }

    /**
     * Sets the third line of the address.
     * @param string $addressLine3
     */
    public function setAddressLine3(string $addressLine3): void
    {
        $this->addressLine3 = $addressLine3;
    }

    /**
     * Returns the city of the address.
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the city of the address.
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Returns the county of the address.
     * @return string
     */
    public function getCounty(): string
    {
        return $this->county;
    }

    /**
     * Sets the county of the address.
     * @param string $county
     */
    public function setCounty(string $county): void
    {
        $this->county = $county;
    }

    /**
     * Returns the postcode of the address.
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * Sets the postcode of the address.
     * @param string $postcode
     */
    public function setPostcode(string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * Returns the country of the address.
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets the country of the address.
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}
