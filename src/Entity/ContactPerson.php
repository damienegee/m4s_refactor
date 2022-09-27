<?php

namespace App\Entity;

class ContactPerson extends Serialize
{
    private $id;

    private $firstname;

    private $lastname;

    private $email;

    private $phone;

    private $children = array();


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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Customer[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(Customer $child): self
    {
        if (!in_array($child, $this->children)) {
            array_push($this->children, $child);
            $child->addParent($this);
        }

        return $this;
    }
}
