<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Enum\Role;

#[ORM\Entity]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idutilisateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $nomutilisateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenomutilisateur;

    #[ORM\Column(type: "integer")]
    private int $ageutilisateur;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $emailutilisateur;

    #[ORM\Column(type: "string", length: 255)]
    private string $motdepasseutilisateur;

    #[ORM\Column(type: "string", enumType: Role::class)]
    private Role $role;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $resettoken = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $profilepictureurl = null;
   

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $faceEmbedding = null;

    #[ORM\Column]
    private bool $isBanned = false;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Affectationhebergement::class)]
    private Collection $affectationhebergements;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Affectationinterview::class)]
    private Collection $affectationinterviews;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Brancheentreprise::class)]
    private Collection $brancheentreprises;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Offre::class)]
    private Collection $offres;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Repondre::class)]
    private Collection $repondres;

    // Getters and setters for existing fields
    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $value): void
    {
        $this->idutilisateur = $value;
    }

    public function getNomutilisateur(): string
    {
        return $this->nomutilisateur;
    }

    public function setNomutilisateur(string $value): void
    {
        $this->nomutilisateur = $value;
    }

    public function getPrenomutilisateur(): string
    {
        return $this->prenomutilisateur;
    }

    public function setPrenomutilisateur(string $value): void
    {
        $this->prenomutilisateur = $value;
    }

    public function getAgeutilisateur(): int
    {
        return $this->ageutilisateur;
    }

    public function setAgeutilisateur(int $value): void
    {
        $this->ageutilisateur = $value;
    }

    public function getEmailutilisateur(): string
    {
        return $this->emailutilisateur;
    }

    public function setEmailutilisateur(string $value): void
    {
        $this->emailutilisateur = $value;
    }

    public function getMotdepasseutilisateur(): string
    {
        return $this->motdepasseutilisateur;
    }

    public function setMotdepasseutilisateur(string $value): void
    {
        $this->motdepasseutilisateur = $value;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getResettoken(): ?string
    {
        return $this->resettoken;
    }

    public function setResettoken(?string $value): void
    {
        $this->resettoken = $value;
    }

    public function getProfilepictureurl(): ?string
    {
        return $this->profilepictureurl;
    }

    public function setProfilepictureurl(?string $value): void
    {
        $this->profilepictureurl = $value;
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function getPassword(): string
    {
        return $this->motdepasseutilisateur;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->emailutilisateur;
    }

    public function getUserIdentifier(): string
    {
        return $this->emailutilisateur;
    }

    public function eraseCredentials(): void
    {

        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFaceEmbedding(): ?string
    {
        return $this->faceEmbedding;
    }

    public function setFaceEmbedding(?string $faceEmbedding): self
    {
        $this->faceEmbedding = $faceEmbedding;
        return $this;
    }

    public function isFaceRegistered(): bool
    {
        return !empty($this->faceEmbedding);
    }

    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): static
    {
        $this->isBanned = $isBanned;
        return $this;
    }

}
