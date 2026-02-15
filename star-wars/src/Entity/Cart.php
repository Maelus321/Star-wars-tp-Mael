<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: "cart")]
    private ?User $user = null; // Rendre nullable

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: "carts")]
    #[ORM\JoinTable(name: "cart_product")]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection(); // INITIALISATION IMPORTANTE !
    }

    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of user
     */ 
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the value of products
     */ 
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }
        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);
        return $this;
    }
}