<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

//A user of the application
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //The lastname of the user
    #[ORM\Column(length: 180, unique: true)]
    private ?string $last_name = null;

    //The firstname of the user
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $first_name = null;

    //The date of the user's creation
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    //The user's phone number
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BookRenting::class)]
    private Collection $bookRentings;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BookRenting::class, orphanRemoval: true)]
    private Collection $bookRenting;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->bookRentings = new ArrayCollection();
        $this->bookRenting = new ArrayCollection();
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->last_name;
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
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, BookRenting>
     */
    public function getBookRentings(): Collection
    {
        return $this->bookRentings;
    }

    public function addBookRenting(BookRenting $bookRenting): self
    {
        if (!$this->bookRentings->contains($bookRenting)) {
            $this->bookRentings->add($bookRenting);
            $bookRenting->setUser($this);
        }

        return $this;
    }

    public function removeBookRenting(BookRenting $bookRenting): self
    {
        if ($this->bookRentings->removeElement($bookRenting)) {
            // set the owning side to null (unless already changed)
            if ($bookRenting->getUser() === $this) {
                $bookRenting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookRenting>
     */
    public function getBookRenting(): Collection
    {
        return $this->bookRenting;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
