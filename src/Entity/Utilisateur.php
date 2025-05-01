<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Enum\Role;
use App\Entity\Affectationhebergement;
use App\Entity\Affectationinterview;
use App\Entity\Brancheentreprise;
use App\Entity\Publication;
use App\Entity\Offre;
use App\Entity\Repondre;

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

    public function __construct()
    {
        $this->affectationhebergements = new ArrayCollection();
        $this->affectationinterviews = new ArrayCollection();
        $this->brancheentreprises = new ArrayCollection();
        $this->publications = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->repondres = new ArrayCollection();
    }

    // Getters and setters
    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $value): self
    {
        $this->idutilisateur = $value;
        return $this;
    }

    public function getNomutilisateur(): string
    {
        return $this->nomutilisateur;
    }

    public function setNomutilisateur(string $value): self
    {
        $this->nomutilisateur = $value;
        return $this;
    }

    public function getPrenomutilisateur(): string
    {
        return $this->prenomutilisateur;
    }

    public function setPrenomutilisateur(string $value): self
    {
        $this->prenomutilisateur = $value;
        return $this;
    }

    public function getAgeutilisateur(): int
    {
        return $this->ageutilisateur;
    }

    public function setAgeutilisateur(int $value): self
    {
        $this->ageutilisateur = $value;
        return $this;
    }

    public function getEmailutilisateur(): string
    {
        return $this->emailutilisateur;
    }

    public function setEmailutilisateur(string $value): self
    {
        $this->emailutilisateur = $value;
        return $this;
    }

    public function getMotdepasseutilisateur(): string
    {
        return $this->motdepasseutilisateur;
    }

    public function setMotdepasseutilisateur(string $value): self
    {
        $this->motdepasseutilisateur = $value;
        return $this;
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

    public function setResettoken(?string $value): self
    {
        $this->resettoken = $value;
        return $this;
    }

    public function getProfilepictureurl(): ?string
    {
        return $this->profilepictureurl;
    }

    public function setProfilepictureurl(?string $value): self
    {
        $this->profilepictureurl = $value;
        return $this;
    }

    // Collection methods
    public function getAffectationhebergements(): Collection
    {
        return $this->affectationhebergements;
    }

    public function addAffectationhebergement(Affectationhebergement $affectationhebergement): self
    {
        if (!$this->affectationhebergements->contains($affectationhebergement)) {
            $this->affectationhebergements[] = $affectationhebergement;
            $affectationhebergement->setIdutilisateur($this);
        }
        return $this;
    }

    public function removeAffectationhebergement(Affectationhebergement $affectationhebergement): self
    {
        if ($this->affectationhebergements->removeElement($affectationhebergement)) {
            if ($affectationhebergement->getIdutilisateur() === $this) {
                $affectationhebergement->setIdutilisateur(null);
            }
        }
        return $this;
    }

    public function getAffectationinterviews(): Collection
    {
        return $this->affectationinterviews;
    }

    public function addAffectationinterview(Affectationinterview $affectationinterview): self
    {
        if (!$this->affectationinterviews->contains($affectationinterview)) {
            $this->affectationinterviews[] = $affectationinterview;
            $affectationinterview->setIdutilisateur($this);
        }
        return $this;
    }

    public function removeAffectationinterview(Affectationinterview $affectationinterview): self
    {
        if ($this->affectationinterviews->removeElement($affectationinterview)) {
            if ($affectationinterview->getIdutilisateur() === $this) {
                $affectationinterview->setIdutilisateur(null);
            }
        }
        return $this;
    }

    // UserInterface methods
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

    // Face recognition methods
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

    // Ban status methods
    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;
        return $this;
    }

    // Add similar collection methods for other relations (brancheentreprises, publications, offres, repondres) as needed
}