<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity("username", message="Le nom est déjà pris")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 4,
     *      max = 10,
     *      minMessage = "Le nom est trop court",
     *      maxMessage = "Le nom est trop long"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 8,
     *      max = 100,
     *      minMessage = "Le mot de passe est trop court",
     *      maxMessage = "Le mot de passe est trop long"
     * )
     */
    private $password;

   /**
     * @Assert\EqualTo(propertyPath="password", message = "Le mot de passe n'est pas identique")
     */
    private $password_confirm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

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

    public function getPasswordConfirm(): ?string
    {
        return $this->password_confirm;
    }

    public function setPasswordConfirm(string $password_confirm): self
    {
        $this->password_confirm = $password_confirm;

        return $this;
    }

    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
    }
}
