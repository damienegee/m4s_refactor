<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Loan::class, inversedBy="images")
     */
    private $loans;

    /**
     * @ORM\ManyToOne(targetEntity=ReturnedLoan::class, inversedBy="images")
     */
    private $returnedLoan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLoans(): ?Loan
    {
        return $this->loans;
    }

    public function setLoans(?Loan $loans): self
    {
        $this->loans = $loans;

        return $this;
    }

    public function getReturnedLoan(): ?ReturnedLoan
    {
        return $this->returnedLoan;
    }

    public function setReturnedLoan(?ReturnedLoan $returnedLoan): self
    {
        $this->returnedLoan = $returnedLoan;

        return $this;
    }
}
