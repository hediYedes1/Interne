<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Departmententreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $iddepartement;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "departmententreprises")]
    #[ORM\JoinColumn(name: 'identreprise', referencedColumnName: 'identreprise', onDelete: 'CASCADE')]
    private Entreprise $identreprise;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le nom du département est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom du département ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $nomdepartement;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description du département est obligatoire.")]
    #[Assert\Length(
        max: 500,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $descriptiondepartement;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le responsable du département est obligatoire.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "Le nom du responsable ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $responsabledepartement;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "Le nombre d'employés est obligatoire.")]
    #[Assert\Positive(message: "Le nombre d'employés doit être un nombre positif.")]
    private int $nbremployedepartement;

    public function getIddepartement(): int
    {
        return $this->iddepartement;
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

    public function getNomdepartement(): string
    {
        return $this->nomdepartement;
    }

    public function setNomdepartement(string $nomdepartement): self
    {
        $this->nomdepartement = $nomdepartement;

        return $this;
    }

    public function getDescriptiondepartement(): string
    {
        return $this->descriptiondepartement;
    }

    public function setDescriptiondepartement(string $descriptiondepartement): self
    {
        $this->descriptiondepartement = $descriptiondepartement;

        return $this;
    }

    public function getResponsabledepartement(): string
    {
        return $this->responsabledepartement;
    }

    public function setResponsabledepartement(string $responsabledepartement): self
    {
        $this->responsabledepartement = $responsabledepartement;

        return $this;
    }

    public function getNbremployedepartement(): int
    {
        return $this->nbremployedepartement;
    }

    public function setNbremployedepartement(int $nbremployedepartement): self
    {
        $this->nbremployedepartement = $nbremployedepartement;

        return $this;
    }
}