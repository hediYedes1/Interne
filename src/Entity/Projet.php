<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Offre;

#[ORM\Entity]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $idprojet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre du projet est requis.")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $titreprojet = null;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description est requise.")]
    #[Assert\Length(
        min: 10,
        minMessage: "La description doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $descriptionprojet = null;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date de début est requise.")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date de début est invalide.")]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date de fin est requise.")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date de fin est invalide.")]
    #[Assert\Expression(
        "this.getDatefin() >= this.getDatedebut()",
        message: "La date de fin doit être postérieure ou égale à la date de début."
    )]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: "projets")]
    #[ORM\JoinColumn(nullable: false, name: "idoffre", referencedColumnName: "idoffre", onDelete: "CASCADE")]
    #[Assert\NotNull(message: "Veuillez sélectionner une offre.")]
    private ?Offre $idoffre = null;

    // Getters & Setters

    public function getIdprojet(): ?int
    {
        return $this->idprojet;
    }

    public function setIdprojet(int $idprojet): self
    {
        $this->idprojet = $idprojet;
        return $this;
    }

    public function getTitreprojet(): ?string
    {
        return $this->titreprojet;
    }

    public function setTitreprojet(string $titreprojet): self
    {
        $this->titreprojet = $titreprojet;
        return $this;
    }

    public function getDescriptionprojet(): ?string
    {
        return $this->descriptionprojet;
    }

    public function setDescriptionprojet(string $descriptionprojet): self
    {
        $this->descriptionprojet = $descriptionprojet;
        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;
        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;
        return $this;
    }

    public function getIdoffre(): ?Offre
    {
        return $this->idoffre;
    }

    public function setIdoffre(?Offre $idoffre): self
    {
        $this->idoffre = $idoffre;
        return $this;
    }
}
