<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class User
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    

    #[ORM\Column(length:100, nullable:false, unique:true)]
    #[Assert\NotBlank(message:"Username cannot be empty.")]
    #[Assert\Length(
        min:4,
        max:100,
        maxMessage:"Username must have less than 100 characters",
        minMessage:"Username must have more than 4 characters"
    )]
    private string $username;

    #[ORM\Column(length:100, nullable:false, unique:false)]
    #[Assert\NotBlank(message:"Password cannot be empty.")]
    #[Assert\Length(
        min:8,
        max:100,
        maxMessage:"Password must have less than 100 characters",
        minMessage:"Password must have more than 8 characters"
    )]
    private string $password;

    #[ORM\Column(length:100, nullable:false, unique:false, type:"json")]
    private array $roles = [];


    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

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
}