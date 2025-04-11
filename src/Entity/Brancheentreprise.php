<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Partenariat;

#[ORM\Entity]
class Brancheentreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue] // Ajout pour générer automatiquement l'ID
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

    public function getIdbranche(): int
    {
        return $this->idbranche;
    }

    public function getIdentreprise(): Entreprise
    {
        return $this->identreprise;
    }

    public function setIdentreprise(Entreprise $identreprise): self
    {
        $this->identreprise = $identreprise;

        return $this;
    }

    public function getIdutilisateur(): Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(Utilisateur $idutilisateur): self
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }

    public function getLocalisationbranche(): string
    {
        return $this->localisationbranche;
    }

    public function setLocalisationbranche(string $localisationbranche): self
    {
        $this->localisationbranche = $localisationbranche;

        return $this;
    }

    public function getEmailbranche(): string
    {
        return $this->emailbranche;
    }

    public function setEmailbranche(string $emailbranche): self
    {
        $this->emailbranche = $emailbranche;

        return $this;
    }

    public function getContactbranche(): string
    {
        return $this->contactbranche;
    }

    public function setContactbranche(string $contactbranche): self
    {
        $this->contactbranche = $contactbranche;

        return $this;
    }

    public function getNombreemploye(): int
    {
        return $this->nombreemploye;
    }

    public function setNombreemploye(int $nombreemploye): self
    {
        $this->nombreemploye = $nombreemploye;

        return $this;
    }

    public function getResponsablebranche(): string
    {
        return $this->responsablebranche;
    }

    public function setResponsablebranche(string $responsablebranche): self
    {
        $this->responsablebranche = $responsablebranche;

        return $this;
    }

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
            // set the owning side to null (unless already changed)
            if ($partenariat->getIdbranche() === $this) {
                $partenariat->setIdbranche(null);
            }
        }

        return $this;
    }
}