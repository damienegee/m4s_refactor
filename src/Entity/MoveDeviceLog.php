<?php

namespace App\Entity;

use App\Repository\MoveDeviceLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveDeviceLogRepository::class)
 */
class MoveDeviceLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $deviceId;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $deviceSerial;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fromLocationId;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
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

    public function getDeviceId(): ?int
    {
        return $this->deviceId;
    }

    public function setDeviceId(int $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getDeviceSerial(): ?string
    {
        return $this->deviceSerial;
    }

    public function setDeviceSerial(string $deviceSerial): self
    {
        $this->deviceSerial = $deviceSerial;

        return $this;
    }

    public function getFromLocationId(): ?int
    {
        return $this->fromLocationId;
    }

    public function setFromLocationId($fromLocationId): self
    {
        $this->fromLocationId = $fromLocationId;

        return $this;
    }

    public function getFromLocationName(): ?string
    {
        return $this->fromLocationName;
    }

    public function setFromLocationName($fromLocationName): self
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
        return $this->id;
    }
}
