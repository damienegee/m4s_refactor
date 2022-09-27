<?php

namespace App\Entity;

use App\Repository\ReturnedLoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReturnedLoanRepository::class)
 */
class ReturnedLoan
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
    private $returneddate;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="returnedLoan", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarks;

    /**
     * @ORM\OneToOne(targetEntity=Loan::class, inversedBy="returnedLoan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $loan;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReturneddate(): ?\DateTimeInterface
    {
        return $this->returneddate;
    }

    public function setReturneddate(\DateTimeInterface $returneddate): self
    {
        $this->returneddate = $returneddate;

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
            $image->setReturnedLoan($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getReturnedLoan() === $this) {
                $image->setReturnedLoan(null);
            }
        }

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getLoan(): ?Loan
    {
        return $this->loan;
    }

    public function setLoan(Loan $loan): self
    {
        $this->loan = $loan;

        return $this;
    }
}
