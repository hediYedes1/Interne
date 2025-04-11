<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Entity\Partenariat;

#[ORM\Entity]
class Brancheentreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idbranche;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "brancheentreprises")]
    #[ORM\JoinColumn(name: 'identreprise', referencedColumnName: 'identreprise', onDelete: 'CASCADE')]
    private Entreprise $identreprise;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "brancheentreprises")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "text")]
    private string $localisationbranche;

    #[ORM\Column(type: "text")]
    private string $emailbranche;

    #[ORM\Column(type: "string", length: 15)]
    private string $contactbranche;

    #[ORM\Column(type: "integer")]
    private int $nombreemploye;

    #[ORM\Column(type: "text")]
    private string $responsablebranche;

    #[ORM\OneToMany(mappedBy: "idbranche", targetEntity: Partenariat::class)]
    private Collection $partenariats;

    public function __construct()
    {
        $this->partenariats = new ArrayCollection();
    }

    // ID
    public function getId(): ?int
    {
        return $this->idbranche;
    }

    public function getIdbranche(): ?int
    {
        return $this->idbranche;
    }

    public function setIdbranche(int $value): void
    {
        $this->idbranche = $value;
    }

    // Entreprise
    public function getIdentreprise(): Entreprise
    {
        return $this->identreprise;
    }

    public function setIdentreprise(Entreprise $value): void
    {
        $this->identreprise = $value;
    }

    // Utilisateur
    public function getIdutilisateur(): Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(Utilisateur $value): void
    {
        $this->idutilisateur = $value;
    }

    // Localisation
    public function getLocalisationbranche(): string
    {
        return $this->localisationbranche;
    }

    public function setLocalisationbranche(string $value): void
    {
        $this->localisationbranche = $value;
    }

    // Email
    public function getEmailbranche(): string
    {
        return $this->emailbranche;
    }

    public function setEmailbranche(string $value): void
    {
        $this->emailbranche = $value;
    }

    // Contact
    public function getContactbranche(): string
    {
        return $this->contactbranche;
    }

    public function setContactbranche(string $value): void
    {
        $this->contactbranche = $value;
    }

    // Nombre d'employés
    public function getNombreemploye(): int
    {
        return $this->nombreemploye;
    }

    public function setNombreemploye(int $value): void
    {
        $this->nombreemploye = $value;
    }

    // Responsable
    public function getResponsablebranche(): string
    {
        return $this->responsablebranche;
    }

    public function setResponsablebranche(string $value): void
    {
        $this->responsablebranche = $value;
    }

    // Partenariats
    public function getPartenariats(): Collection
    {
        return $this->partenariats;
    }

    public function addPartenariat(Partenariat $partenariat): self
    {
        if (!$this->partenariats->contains($partenariat)) {
            $this->partenariats[] = $partenariat;
            $partenariat->setIdbranche($this);
        }

        return $this;
    }

    public function removePartenariat(Partenariat $partenariat): self
    {
        if ($this->partenariats->removeElement($partenariat)) {
            if ($partenariat->getIdbranche() === $this) {
                $partenariat->setIdbranche(null);
            }
        }

        return $this;
    }


    public function __toString(): string
{
    return ' (Branche : ' . $this->getContactbranche() . ')';
}

}
