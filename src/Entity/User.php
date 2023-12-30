<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

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
    public function getToken(): ?string
    {
        return $this->token;
    }
    public function setToken(string $token): self
    {
        $this->token = $token == '' ? null : $token;
        return $this;
    }
    public function getRoles(): array
    {
        // Puedes personalizar esto según tus necesidades
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        //
    }
    public function getUserIdentifier()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        //
    }
}
