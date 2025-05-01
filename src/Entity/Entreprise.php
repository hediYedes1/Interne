<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Departmententreprise;
use App\Entity\Brancheentreprise;

#[ORM\Entity]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $identreprise;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le nom de l'entreprise ne peut pas être vide.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le nom de l'entreprise doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom de l'entreprise ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $nomentreprise;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description de l'entreprise ne peut pas être vide.")]
    #[Assert\Length(
        min: 10,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        max: 500,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $descriptionentreprise;

    #[ORM\Column(type: "string", length: 255)]
    private string $logoentreprise;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "L'URL de l'entreprise est requise.")]
    #[Assert\Regex(
        pattern: "/^www\.[a-z0-9\-]+\.[a-z]{2,6}(\.[a-z]{2})?$/i",
        message: "L'URL doit commencer par 'www.' et être au format valide."
    )]
    private string $urlentreprise;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le secteur de l'entreprise est obligatoire.")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le secteur doit contenir au moins {{ limit }} caractères.",
        max: 255,
        maxMessage: "Le secteur ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $secteurentreprise;

    #[ORM\OneToMany(mappedBy: "identreprise", targetEntity: Departmententreprise::class)]
    private Collection $departmententreprises;

    #[ORM\OneToMany(mappedBy: "identreprise", targetEntity: Brancheentreprise::class)]
    private Collection $brancheentreprises;

    #[ORM\OneToMany(mappedBy: "identreprise", targetEntity: Offre::class)]
    private Collection $offres;

    public function __construct()
    {
        $this->departmententreprises = new ArrayCollection();
        $this->brancheentreprises = new ArrayCollection();
        $this->offres = new ArrayCollection();
    }

    public function getIdentreprise(): int
    {
        return $this->identreprise;
    }

    public function setIdentreprise(int $value): self
    {
        $this->identreprise = $value;

        return $this;
    }

    public function getNomentreprise(): string
    {
        return $this->nomentreprise;
    }

    public function setNomentreprise(string $value): self
    {
        $this->nomentreprise = $value;

        return $this;
    }

    public function getDescriptionentreprise(): string
    {
        return $this->descriptionentreprise;
    }

    public function setDescriptionentreprise(string $value): self
    {
        $this->descriptionentreprise = $value;

        return $this;
    }

    public function getLogoentreprise(): string
    {
        return $this->logoentreprise;
    }

    public function setLogoentreprise(string $value): self
    {
        $this->logoentreprise = $value;
        return $this;
    }

    public function getUrlentreprise(): string
    {
        return $this->urlentreprise;
    }

    public function setUrlentreprise(string $value): self
    {
        $this->urlentreprise = $value;

        return $this;
    }

    public function getSecteurentreprise(): string
    {
        return $this->secteurentreprise;
    }

    public function setSecteurentreprise(string $value): self
    {
        $this->secteurentreprise = $value;

        return $this;
    }

    public function getDepartmententreprises(): Collection
    {
        return $this->departmententreprises;
    }

    public function addDepartmententreprise(Departmententreprise $departmententreprise): self
    {
        if (!$this->departmententreprises->contains($departmententreprise)) {
            $this->departmententreprises[] = $departmententreprise;
            $departmententreprise->setIdentreprise($this);
        }

        return $this;
    }

    public function removeDepartmententreprise(Departmententreprise $departmententreprise): self
    {
        if ($this->departmententreprises->removeElement($departmententreprise)) {
            if ($departmententreprise->getIdentreprise() === $this) {
                $departmententreprise->setIdentreprise(null);
                
            }
        }

        return $this;
    }

    public function getBrancheentreprises(): Collection
    {
        return $this->brancheentreprises;
    }

    public function addBrancheentreprise(Brancheentreprise $brancheentreprise): self
    {
        if (!$this->brancheentreprises->contains($brancheentreprise)) {
            $this->brancheentreprises[] = $brancheentreprise;
            $brancheentreprise->setIdentreprise($this);
        }

        return $this;
    }

    public function removeBrancheentreprise(Brancheentreprise $brancheentreprise): self
    {
        if ($this->brancheentreprises->removeElement($brancheentreprise)) {
            if ($brancheentreprise->getIdentreprise() === $this) {
                $brancheentreprise->setIdentreprise(null);
            }
        }

        return $this;
    }

    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setIdentreprise($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            if ($offre->getIdentreprise() === $this) {
                $offre->setIdentreprise(null);
            }
        }

        return $this;
    }
} 