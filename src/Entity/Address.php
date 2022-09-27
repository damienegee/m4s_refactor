<?php

namespace App\Entity;

class Address extends Serialize
{
    private $id;

    private $city;

    private $zip_code;

    private $street;

    private $number;

    private $bus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zip_code;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zip_code = $zipCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBus(): ?string
    {
        return $this->bus;
    }

    public function setBus(?string $bus): self
    {
        $this->bus = $bus;

        return $this;
    }

    public function __toString() {
        $ret = $this->getStreet() . " " . 
               $this->getNumber() . 
               (($this->getBus()) ? "/" . $this->getBus() : '' ) . " - " . 
               $this->getZipCode() . ", " . 
               $this->getCity(); 
        return $ret;
    }
}
