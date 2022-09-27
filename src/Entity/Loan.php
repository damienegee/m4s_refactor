<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $startdate;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $user;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $enddate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\Column(type="text")
     */
    private $signature;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="loans", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="integer")
     */
    private $schoollocationId;

    /**
     * @ORM\OneToOne(targetEntity=ReturnedLoan::class, mappedBy="loan", cascade={"persist", "remove"})
     */
    private $returnedLoan;

    /**
     * @ORM\Column(type="integer")
     */
    private $deviceId;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $deviceSerial;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isExtra;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setLoans($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getLoans() === $this) {
                $image->setLoans(null);
            }
        }

        return $this;
    }

    public function getSchoolloationId(): ?int {
        return $this->schoollocationId;
    }

    public function setSchoollocationId($schoollocationId) {
        $this->schoollocationId = $schoollocationId;
    }

    public function getReturnedLoan(): ?ReturnedLoan
    {
        return $this->returnedLoan;
    }

    public function setReturnedLoan(ReturnedLoan $returnedLoan): self
    {
        // set the owning side of the relation if necessary
        if ($returnedLoan->getLoan() !== $this) {
            $returnedLoan->setLoan($this);
        }

        $this->returnedLoan = $returnedLoan;

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

    public function getIsExtra(): ?bool
    {
        return $this->isExtra;
    }

    public function setIsExtra(?bool $isExtra): self
    {
        $this->isExtra = $isExtra;

        return $this;
    }
}
