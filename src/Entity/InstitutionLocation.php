<?php

namespace App\Entity;

use App\Entity\Institution;

class InstitutionLocation extends Serialize
{
    private $id;

    private $institutionname;

    private $institutionnumber;

    private $address;

    private $institution;

    private $customers = array();

    private $devices = array();

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ?self
    {
        $this->id = $id;
        return $this;
    }

    public function getInstitutionname(): ?string
    {
        return $this->institutionname;
    }

    public function setInstitutionname(string $institutionName): self
    {
        $this->institutionname = $institutionName;

        return $this;
    }

    public function getInstitutionnumber(): ?string
    {
        return $this->institutionnumber;
    }

    public function setInstitutionnumber(?string $institutionNumber): self
    {
        $this->institutionnumber = $institutionNumber;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getInstitution(): ?Institution
    {
        return $this->institution;
    }

    public function setInstitution(?Institution $institution): self
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * @return Customer[]
     */
    public function getCustomers(): array
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        // if (!$this->customers->contains($customer)) {
        //     $this->customers[] = $customer;
        //     $customer->setSchoolLocation($this);
        // }
        if (!in_array($customer, $this->customers)) {
            array_push($this->customers, $customer);
            $customer->setSchoolLocation($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        // if ($this->customers->removeElement($customer)) {
        //     // set the owning side to null (unless already changed)
        //     if ($customer->getSchoolLocation() === $this) {
        //         $customer->setSchoolLocation(null);
        //     }
        // }

        return $this;
    }

    /**
     * @return Device[]
     */
    public function getDevices(): array
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        // if (!$this->devices->contains($device)) {
        //     $this->devices[] = $device;
        //     $device->setSchoolLocation($this);
        // }
        if (!in_array($device, $this->devices)) {
            array_push($this->devices, $device);
            $device->setSchoolLocation($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        // if ($this->devices->removeElement($device)) {
        //     // set the owning side to null (unless already changed)
        //     if ($device->getSchoolLocation() === $this) {
        //         $device->setSchoolLocation(null);
        //     }
        // }

        return $this;
    }
}
