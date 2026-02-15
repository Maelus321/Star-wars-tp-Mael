<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: false, unique: true)]
    #[Assert\NotBlank(message: "Username cannot be empty.")]
    #[Assert\Length(
        min: 4,
        max: 100,
        maxMessage: "Username must have less than 100 characters",
        minMessage: "Username must have more than 4 characters"
    )]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $password = null; // Plus de contrainte de validation ici !

    /**
     * @var string|null Le mot de passe en clair (non persisté)
     */
    #[Assert\NotBlank(message: "Password cannot be empty.", groups: ["creation"])]
    #[Assert\Length(
        min: 8,
        max: 4096,
        minMessage: "Password must have more than 8 characters",
        groups: ["creation", "Default"]
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\OneToOne(targetEntity: Cart::class, mappedBy: "user", orphanRemoval: true, cascade: ["persist"])]
    private ?Cart $cart = null;

    // UserInterface methods
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function __construct()
{
}

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;
        return $this;
    }
}