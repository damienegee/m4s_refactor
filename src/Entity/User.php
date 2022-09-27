<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $synergy_id;

    /**
     * @ORM\ManyToMany(targetEntity=Institution::class, mappedBy="users", cascade={"persist"})
     */
    private $institutions;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $locale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showRelease;

    const ROLES = array(
        'Admin' => 'ROLE_ADMIN',
        'AGSO' => 'ROLE_AGSO',
        'User' => 'ROLE_USER',
        'SchoolAdmin' => 'ROLE_SCHOOLADMIN'
    );

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return array_search('ROLE_ADMIN', $this->roles) !== false;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSynergyId(): ?int
    {
        return $this->synergy_Id;
    }

    public function setSynergyId(int $synergyId): self
    {
        $this->synergy_id = $synergyId;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Institution[]
     */
    public function getInstitutions(): Collection
    {
        return $this->institutions;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function hasInstitutionBySid(string $id)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('synergy_id', intval($id)));
        return $this->institutions->matching($criteria)->count();
    }

    public function addInstitution(Institution $institution): self
    {
        if (!$this->institutions->contains($institution)) {
            $this->institutions[] = $institution;
            $institution->addUser($this);
        }

        return $this;
    }

    public function removeInstitution(Institution $institution): self
    {
        if ($this->institutions->removeElement($institution)) {
            $institution->removeUser($this);
        }

        return $this;
    }

    public function containsInstitution(Institution $institution): bool
    {
        return $this->institutions->contains($institution);
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getShowRelease(): ?bool
    {
        return $this->showRelease;
    }

    public function setShowRelease(bool $showRelease): self
    {
        $this->showRelease = $showRelease;

        return $this;
    }
}
