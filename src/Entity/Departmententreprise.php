<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Entreprise;

#[ORM\Entity]
class Departmententreprise
{

    #[ORM\Id]
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

    public function getIddepartement()
    {
        return $this->iddepartement;
    }

    public function setIddepartement($value)
    {
        $this->iddepartement = $value;
    }

    public function getIdentreprise()
    {
        return $this->identreprise;
    }

    public function setIdentreprise($value)
    {
        $this->identreprise = $value;
    }

    public function getNomdepartement()
    {
        return $this->nomdepartement;
    }

    public function setNomdepartement($value)
    {
        $this->nomdepartement = $value;
    }

    public function getDescriptiondepartement()
    {
        return $this->descriptiondepartement;
    }

    public function setDescriptiondepartement($value)
    {
        $this->descriptiondepartement = $value;
    }

    public function getResponsabledepartement()
    {
        return $this->responsabledepartement;
    }

    public function setResponsabledepartement($value)
    {
        $this->responsabledepartement = $value;
    }

    public function getNbremployedepartement()
    {
        return $this->nbremployedepartement;
    }

    public function setNbremployedepartement($value)
    {
        $this->nbremployedepartement = $value;
    }
}
