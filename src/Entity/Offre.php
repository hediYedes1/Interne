<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Entreprise;
use App\Entity\Interview;
use App\Entity\Projet;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idoffre;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: 'identreprise', referencedColumnName: 'identreprise', onDelete: 'CASCADE')]
    private Entreprise $identreprise;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le titre de l'offre est obligatoire.")]
    private string $titreoffre;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description de l'offre est obligatoire.")]
    private string $descriptionoffre;

    #[ORM\Column(type: "float")]
    #[Assert\NotBlank(message: "Le salaire est obligatoire.")]
    #[Assert\Positive(message: "Le salaire doit être un nombre positif.")]
    private float $salaireoffre;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La localisation est obligatoire.")]
    private string $localisationoffre;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "L'adresse email du RH est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer un email valide.")]
    private string $emailrh;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le type de contrat est requis.")]
    private string $typecontrat;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date limite est requise.")]
    #[Assert\GreaterThan("today", message: "La date limite doit être dans le futur.")]
    private \DateTimeInterface $datelimite;
    

    #[ORM\OneToMany(mappedBy: "idoffre", targetEntity: Projet::class)]
    private Collection $projets;

    #[ORM\OneToMany(mappedBy: "idoffre", targetEntity: Interview::class)]
    private Collection $interviews;

    public function __construct()
    {
        $this->datelimite = new \DateTime('+1 day');
        $this->projets = new ArrayCollection();
        $this->interviews = new ArrayCollection();
    }

    public function getIdoffre(): int
    {
        return $this->idoffre;
    }

    public function setIdoffre(int $value): void
    {
        $this->idoffre = $value;
    }

    public function getIdutilisateur(): Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(Utilisateur $value): void
    {
        $this->idutilisateur = $value;
    }

    public function getIdentreprise(): Entreprise
    {
        return $this->identreprise;
    }

    public function setIdentreprise(Entreprise $value): void
    {
        $this->identreprise = $value;
    }

    public function getTitreoffre(): string
    {
        return $this->titreoffre;
    }

    public function setTitreoffre(string $value): void
    {
        $this->titreoffre = $value;
    }

    public function getDescriptionoffre(): string
    {
        return $this->descriptionoffre;
    }

    public function setDescriptionoffre(string $value): void
    {
        $this->descriptionoffre = $value;
    }

    public function getSalaireoffre(): float
    {
        return $this->salaireoffre;
    }

    public function setSalaireoffre(float $value): void
    {
        $this->salaireoffre = $value;
    }

    public function getLocalisationoffre(): string
    {
        return $this->localisationoffre;
    }

    public function setLocalisationoffre(string $value): void
    {
        $this->localisationoffre = $value;
    }

    public function getEmailrh(): string
    {
        return $this->emailrh;
    }

    public function setEmailrh(string $value): void
    {
        $this->emailrh = $value;
    }

    public function getTypecontrat(): string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(string $value): void
    {
        $this->typecontrat = $value;
    }

    public function getDatelimite(): \DateTimeInterface
    {
        return $this->datelimite;
    }

    public function setDatelimite(\DateTimeInterface $value): void
    {
        $this->datelimite = $value;
    }

    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function getInterviews(): Collection
    {
        return $this->interviews;
    }
}
