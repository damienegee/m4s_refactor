<?php

namespace App\Entity;

use App\Repository\MoveCustomerLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveCustomerLogRepository::class)
 */
class MoveCustomerLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $customerId;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $customerName;

    /**
     * @ORM\Column(type="integer")
     */
    private $fromLocationId;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $fromLocationName;

    /**
     * @ORM\Column(type="integer")
     */
    private $toLocationId;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $toLocationName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $whenMoved;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getFromLocationId(): ?int
    {
        return $this->fromLocationId;
    }

    public function setFromLocationId(int $fromLocationId): self
    {
        $this->fromLocationId = $fromLocationId;

        return $this;
    }

    public function getFromLocationName(): ?string
    {
        return $this->fromLocationName;
    }

    public function setFromLocationName(string $fromLocationName): self
    {
        $this->fromLocationName = $fromLocationName;

        return $this;
    }

    public function getToLocationId(): ?int
    {
        return $this->toLocationId;
    }

    public function setToLocationId(int $toLocationId): self
    {
        $this->toLocationId = $toLocationId;

        return $this;
    }

    public function getToLocationName(): ?string
    {
        return $this->toLocationName;
    }

    public function setToLocationName(string $toLocationName): self
    {
        $this->toLocationName = $toLocationName;

        return $this;
    }

    public function getWhenMoved(): ?\DateTimeInterface
    {
        return $this->whenMoved;
    }

    public function setWhenMoved(\DateTimeInterface $whenMoved): self
    {
        $this->whenMoved = $whenMoved;

        return $this;
    }

    public function __toString()
    {
        return strval($this->id);
    }
}
