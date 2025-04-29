<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Entreprise;
use App\Entity\Interview;
use App\Enum\TypeContrat;
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
    #[ORM\Column(name: "idoffre", type: "integer")]
    private ?int $idoffre = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: "idutilisateur", referencedColumnName: "idutilisateur", nullable: true, onDelete: "CASCADE")]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: "identreprise", referencedColumnName: "identreprise", nullable: true, onDelete: "CASCADE")]
    private ?Entreprise $entreprise = null;

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

    #[ORM\Column(type: "string", enumType: TypeContrat::class)]
    private TypeContrat $typecontrat;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date limite est requise.")]
    #[Assert\GreaterThan("today", message: "La date limite doit être dans le futur.")]
    private \DateTimeInterface $datelimite;

    #[ORM\OneToMany(mappedBy: "offre", targetEntity: Interview::class, orphanRemoval: false, cascade: ["persist", "remove"])]
    private Collection $interviews;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: "idprojet", referencedColumnName: "idprojet", nullable: true, onDelete: "SET NULL")]
    private ?Projet $projet = null;

    public function __construct()
    {
        $this->datelimite = new \DateTime('+1 day');
        $this->interviews = new ArrayCollection();
    }

    // Getters and Setters (no change except corrected types)

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    public function getTitreoffre(): string
    {
        return $this->titreoffre;
    }

    public function setTitreoffre(string $titreoffre): self
    {
        $this->titreoffre = $titreoffre;
        return $this;
    }

    public function getDescriptionoffre(): string
    {
        return $this->descriptionoffre;
    }

    public function setDescriptionoffre(string $descriptionoffre): self
    {
        $this->descriptionoffre = $descriptionoffre;
        return $this;
    }

    public function getSalaireoffre(): float
    {
        return $this->salaireoffre;
    }

    public function setSalaireoffre(float $salaireoffre): self
    {
        $this->salaireoffre = $salaireoffre;
        return $this;
    }

    public function getLocalisationoffre(): string
    {
        return $this->localisationoffre;
    }

    public function setLocalisationoffre(string $localisationoffre): self
    {
        $this->localisationoffre = $localisationoffre;
        return $this;
    }

    public function getEmailrh(): string
    {
        return $this->emailrh;
    }

    public function setEmailrh(string $emailrh): self
    {
        $this->emailrh = $emailrh;
        return $this;
    }

    public function getTypecontrat(): TypeContrat
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(TypeContrat $typecontrat): self
    {
        $this->typecontrat = $typecontrat;
        return $this;
    }

    public function getDatelimite(): \DateTimeInterface
    {
        return $this->datelimite;
    }

    public function setDatelimite(\DateTimeInterface $datelimite): self
    {
        $this->datelimite = $datelimite;
        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;
        return $this;
    }

    /**
     * @return Collection<int, Interview>
     */
    public function getInterviews(): Collection
    {
        return $this->interviews;
    }

    public function addInterview(Interview $interview): self
    {
        if (!$this->interviews->contains($interview)) {
            $this->interviews[] = $interview;
            $interview->setOffre($this);
        }
        return $this;
    }

    public function removeInterview(Interview $interview): self
    {
        if ($this->interviews->removeElement($interview)) {
            if ($interview->getOffre() === $this) {
                $interview->setOffre(null);
            }
        }
        return $this;
    }
}