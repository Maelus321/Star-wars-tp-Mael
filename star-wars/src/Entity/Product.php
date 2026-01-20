<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

enum Category: string {
    case Ship = 'S';
    case Blaster = 'B';
    case Droid = "D";
    case Vehicle = "V";
    case Weapon = "W";
    case Kyber = "K";
}

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length:100, unique:true)]
    #[Assert\NotBlank(message:"Name cannot be empty.")]
    #[Assert\Length(
        min:4,
        max:100,
        maxMessage:"Name must have less than 100 characters",
        minMessage:"Name must have more than 4 characters"
    )]
    private string $name;

    #[ORM\Column(type:"text")]
    #[Assert\NotBlank(message:"Description cannot be empty.")]
    #[Assert\Length(
        min:4,
        max:500,
        maxMessage:"Description must have less than 500 characters",
        minMessage:"Description must have more than 4 characters"
    )]
    private string $description;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Price cannot be empty.")]
    private float $price;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Image cannot be empty.")]
    #[Assert\Length(
        min:4,
        max:500,
        maxMessage:"Image must have less than 500 characters",
        minMessage:"Image must have more than 4 characters"
    )]
    private string $image;

    #[ORM\Column(type:"enum")]
    private Category $category;

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of Description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}
