<?php

namespace App\Entity;

class ExtraDevice extends Serialize
{
    // private $customer_id;

    private $m4sSchoollocationId;

    private $productnumber;

    private $manufacturer;

    private $model;

    private $supplier;

    private $serialNumber;

    // public function getCustomer_id(): ?int
    // {
    //     return $this->customer_id;
    // }

    // public function setCustomer_id(?int $customer_id): self
    // {
    //     $this->customer_id = $customer_id;

    //     return $this;
    // }

    public function getM4sSchoollocationId(): ?int
    {
        return $this->m4sSchoollocationId;
    }

    public function setM4sSchoollocationId(int $m4sSchoollocationId): self
    {
        $this->m4sSchoollocationId = $m4sSchoollocationId;

        return $this;
    }

    public function getProductnumber(): ?string
    {
        return $this->productnumber;
    }

    public function setProductnumber(?string $productnumber): self
    {
        $this->productnumber = $productnumber;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getSupplier(): ?string
    {
        return $this->supplier;
    }

    public function setSupplier(?string $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }
}
