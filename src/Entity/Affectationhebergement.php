<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;
use App\Entity\Hebergement;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Affectationhebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: "affectationhebergements")]
    #[ORM\JoinColumn(name: 'idhebergement', referencedColumnName: 'idhebergement', onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "L'hébergement est requis.")]
    private Hebergement $idhebergement;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "affectationhebergements")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "L'utilisateur est requis.")]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "date", nullable: true)]
    #[Assert\NotNull(message: "La date de début est requise.")]
    #[Assert\Type(type: \DateTimeInterface::class, message: "La date de début doit être une date valide.")]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: "date", nullable: true)]
    #[Assert\NotNull(message: "La date de fin est requise.")]
    #[Assert\Type(type: \DateTimeInterface::class, message: "La date de fin doit être une date valide.")]
    #[Assert\GreaterThan(propertyPath: "datedebut", message: "La date de fin doit être postérieure à la date de début.")]
    private ?\DateTimeInterface $datefin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdhebergement(): Hebergement
    {
        return $this->idhebergement;
    }

    public function setIdhebergement(Hebergement $value): void
    {
        $this->idhebergement = $value;
    }

    public function getIdutilisateur(): Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(Utilisateur $value): void
    {
        $this->idutilisateur = $value;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $value): void
    {
        $this->datedebut = $value;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $value): void
    {
        $this->datefin = $value;
    }

    public function __toString(): string
    {
        return 'Affectation du ' . ($this->datedebut?->format('Y-m-d') ?? '???') . ' au ' . ($this->datefin?->format('Y-m-d') ?? '???');
    }
}
