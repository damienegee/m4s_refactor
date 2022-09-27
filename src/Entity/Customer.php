<?php

namespace App\Entity;

class Customer extends Serialize
{
    private $id;

    private $firstname;

    private $lastname;

    private $email;

    private $type;

    private $schoolLocation;

    private $devices = array();

    private $parents = array();

    private $freefieldtag01;

    private $freefieldtag02;

    private $freefieldtag03;

    private $freefieldtag04;

    private $freefieldtag05;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSchoolLocation(): ?InstitutionLocation
    {
        return $this->schoolLocation;
    }

    public function setSchoolLocation(?InstitutionLocation $schoolLocation): self
    {
        $this->schoolLocation = $schoolLocation;

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
        if (!in_array($device, $this->devices)) {
            array_push($this->devices, $device);
            $device->setCustomer($this);
        }

        return $this;
    }

    public function addParent(ContactPerson $parent): self
    {
        if (!in_array($parent, $this->parents)) {
            array_push($this->parents, $parent);
            $parent->addChild($this);
        }

        return $this;
    }

    public function getFreefieldtag01(): ?string
    {
        return $this->freefieldtag01;
    }

    public function setFreefieldtag01(string $freefieldtag01): self
    {
        $this->freefieldtag01 = $freefieldtag01;

        return $this;
    }

    public function getFreefieldtag02(): ?string
    {
        return $this->freefieldtag02;
    }

    public function setFreefieldtag02(string $freefieldtag02): self
    {
        $this->freefieldtag02 = $freefieldtag02;

        return $this;
    }

    public function getFreefieldtag03(): ?string
    {
        return $this->freefieldtag03;
    }

    public function setFreefieldtag03(string $freefieldtag03): self
    {
        $this->freefieldtag03 = $freefieldtag03;

        return $this;
    }

    public function getFreefieldtag04(): ?string
    {
        return $this->freefieldtag04;
    }

    public function setFreefieldtag04(string $freefieldtag04): self
    {
        $this->freefieldtag04 = $freefieldtag04;

        return $this;
    }

    public function getFreefieldtag05(): ?string
    {
        return $this->freefieldtag05;
    }

    public function setFreefieldtag05(string $freefieldtag05): self
    {
        $this->freefieldtag05 = $freefieldtag05;

        return $this;
    }
}
