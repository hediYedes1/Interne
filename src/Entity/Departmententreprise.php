<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private string $nomdepartement;

    #[ORM\Column(type: "text")]
    private string $descriptiondepartement;

    #[ORM\Column(type: "text")]
    private string $responsabledepartement;

    #[ORM\Column(type: "integer")]
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