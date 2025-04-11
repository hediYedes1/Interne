<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\Partenariat;
use App\Entity\Affectationhebergement;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idhebergement;

    #[ORM\ManyToOne(targetEntity: Partenariat::class, inversedBy: "hebergements")]
    #[ORM\JoinColumn(name: 'idpartenariat', referencedColumnName: 'idpartenariat', onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "Un partenariat doit être sélectionné")]
    private Partenariat $idpartenariat;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'hébergement ne peut pas être vide")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
    )]
    private string $nomhebergement;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "L'adresse doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères"
    )]
    private string $adressehebergement;

    #[ORM\Column(type: "string", length: 255)]
#[Assert\NotBlank(message: "Le type d'hébergement doit être spécifié")]
#[Assert\Choice(
    choices: ["Hôtel", "Appartement", "Auberge"],
    message: "Type d'hébergement non valide. Les options valides sont: Hôtel, Appartement, Auberge"
)]
private string $typehebergement;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description ne peut pas être vide")]
    #[Assert\Length(
        min: 20,
        max: 1000,
        minMessage: "La description doit contenir au moins {{ limit }} caractères",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères"
    )]
    private string $descriptionhebergement;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "Le nombre de nuits doit être spécifié")]
    #[Assert\Positive(message: "Le nombre de nuits doit être positif")]
    #[Assert\LessThan(
        value: 365,
        message: "Le nombre de nuits ne peut pas dépasser {{ compared_value }}"
    )]
    private int $nbrnuitehebergement;

    #[ORM\Column(type: "boolean")]
    private bool $disponibilitehebergement = true;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La localisation ne peut pas être vide")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "La localisation doit contenir au moins {{ limit }} caractères",
        maxMessage: "La localisation ne peut pas dépasser {{ limit }} caractères"
    )]
    private string $localisationhebergement;

    #[ORM\Column(type: "float")]
    #[Assert\NotBlank(message: "Le prix doit être spécifié")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    #[Assert\LessThan(
        value: 10000,
        message: "Le prix ne peut pas dépasser {{ compared_value }}"
    )]
    private float $prixhebergement;

    #[ORM\OneToMany(mappedBy: "idhebergement", targetEntity: Affectationhebergement::class)]
    private Collection $affectationhebergements;

    public function getId(): ?int
    {
        return $this->idhebergement;
    }

    public function getIdhebergement(): ?int
    {
        return $this->idhebergement;
    }

    public function setIdhebergement(int $idhebergement): self
    {
        $this->idhebergement = $idhebergement;
        return $this;
    }

    public function getIdpartenariat(): ?Partenariat
    {
        return $this->idpartenariat;
    }

    public function setIdpartenariat(?Partenariat $idpartenariat): self
    {
        $this->idpartenariat = $idpartenariat;
        return $this;
    }

    public function getNomhebergement(): ?string
    {
        return $this->nomhebergement;
    }

    public function setNomhebergement(string $nomhebergement): self
    {
        $this->nomhebergement = $nomhebergement;
        return $this;
    }

    public function getAdressehebergement(): ?string
    {
        return $this->adressehebergement;
    }

    public function setAdressehebergement(string $adressehebergement): self
    {
        $this->adressehebergement = $adressehebergement;
        return $this;
    }

    public function getTypehebergement(): ?string
    {
        return $this->typehebergement;
    }

    public function setTypehebergement(string $typehebergement): self
    {
        $this->typehebergement = $typehebergement;
        return $this;
    }

    public function getDescriptionhebergement(): ?string
    {
        return $this->descriptionhebergement;
    }

    public function setDescriptionhebergement(string $descriptionhebergement): self
    {
        $this->descriptionhebergement = $descriptionhebergement;
        return $this;
    }

    public function getNbrnuitehebergement(): ?int
    {
        return $this->nbrnuitehebergement;
    }

    public function setNbrnuitehebergement(int $nbrnuitehebergement): self
    {
        $this->nbrnuitehebergement = $nbrnuitehebergement;
        return $this;
    }

    public function getDisponibilitehebergement(): ?bool
    {
        return $this->disponibilitehebergement;
    }

    public function setDisponibilitehebergement(bool $disponibilitehebergement): self
    {
        $this->disponibilitehebergement = $disponibilitehebergement;
        return $this;
    }

    public function getLocalisationhebergement(): ?string
    {
        return $this->localisationhebergement;
    }

    public function setLocalisationhebergement(string $localisationhebergement): self
    {
        $this->localisationhebergement = $localisationhebergement;
        return $this;
    }

    public function getPrixhebergement(): ?float
    {
        return $this->prixhebergement;
    }

    public function setPrixhebergement(float $prixhebergement): self
    {
        $this->prixhebergement = $prixhebergement;
        return $this;
    }

    public function getAffectationhebergements(): Collection
    {
        return $this->affectationhebergements;
    }

    public function __toString(): string
    {
        return $this->nomhebergement ;
    }
}