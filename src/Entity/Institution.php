<?php

namespace App\Entity;

use App\Repository\InstitutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstitutionRepository::class)
 */
class Institution
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
    private $institution_name;

    /**
     * @ORM\Column(type="bigint", length=20, unique=true)
     */
    private $synergy_id;

    private $institutionLocations;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="institutions", cascade={"persist"})
     */
    private $users;

    public function __construct()
    {
        $this->institutionLocations = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstitutionName(): ?string
    {
        return $this->institution_name;
    }

    public function setInstitutionName(string $institutionName): self
    {
        $this->institution_name = $institutionName;

        return $this;
    }

    public function getSynergyId(): ?int
    {
        return $this->synergy_id;
    }

    public function setSynergyId(int $synergyId): self
    {
        $this->synergy_id = $synergyId;

        return $this;
    }

    public function __toString()
    {
        return $this->institution_name;
    }

    public function __equals(Institution $i, Institution $i2)
    {
        return $i->getId() === $i2->getId();
    }

    /**
     * @return Collection|InstitutionLocation[]
     */
    public function getInstitutionLocations(): Collection
    {
        return $this->institutionLocations;
    }

    public function addInstitutionLocation(InstitutionLocation $institutionLocation): self
    {
        if (!$this->institutionLocations->contains($institutionLocation)) {
            $this->institutionLocations[] = $institutionLocation;
            $institutionLocation->setInstitution($this);
        }

        return $this;
    }

    public function removeInstitutionLocation(InstitutionLocation $institutionLocation): self
    {
        if ($this->institutionLocations->removeElement($institutionLocation)) {
            // set the owning side to null (unless already changed)
            if ($institutionLocation->getInstitution() === $this) {
                $institutionLocation->setInstitution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeInstitution($this);
        }

        return $this;
    }

    public function containsUser(User $user): bool
    {
        return $this->users->contains($user);
    }
}
