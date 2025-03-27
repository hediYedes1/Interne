<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Entreprise;

#[ORM\Entity]
class Department_entreprise
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_DEPARTEMENT;

        #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "department_entreprises")]
    #[ORM\JoinColumn(name: 'ID_ENTREPRISE', referencedColumnName: 'ID_ENTREPRISE', onDelete: 'CASCADE')]
    private Entreprise $ID_ENTREPRISE;

    #[ORM\Column(type: "text")]
    private string $NOM_DEPARTEMENT;

    #[ORM\Column(type: "text")]
    private string $DESCRIPTION_DEPARTEMENT;

    #[ORM\Column(type: "text")]
    private string $RESPONSABLE_DEPARTEMENT;

    #[ORM\Column(type: "integer")]
    private int $NBR_EMPLOYE_DEPARTEMENT;

    public function getID_DEPARTEMENT()
    {
        return $this->ID_DEPARTEMENT;
    }

    public function setID_DEPARTEMENT($value)
    {
        $this->ID_DEPARTEMENT = $value;
    }

    public function getID_ENTREPRISE()
    {
        return $this->ID_ENTREPRISE;
    }

    public function setID_ENTREPRISE($value)
    {
        $this->ID_ENTREPRISE = $value;
    }

    public function getNOM_DEPARTEMENT()
    {
        return $this->NOM_DEPARTEMENT;
    }

    public function setNOM_DEPARTEMENT($value)
    {
        $this->NOM_DEPARTEMENT = $value;
    }

    public function getDESCRIPTION_DEPARTEMENT()
    {
        return $this->DESCRIPTION_DEPARTEMENT;
    }

    public function setDESCRIPTION_DEPARTEMENT($value)
    {
        $this->DESCRIPTION_DEPARTEMENT = $value;
    }

    public function getRESPONSABLE_DEPARTEMENT()
    {
        return $this->RESPONSABLE_DEPARTEMENT;
    }

    public function setRESPONSABLE_DEPARTEMENT($value)
    {
        $this->RESPONSABLE_DEPARTEMENT = $value;
    }

    public function getNBR_EMPLOYE_DEPARTEMENT()
    {
        return $this->NBR_EMPLOYE_DEPARTEMENT;
    }

    public function setNBR_EMPLOYE_DEPARTEMENT($value)
    {
        $this->NBR_EMPLOYE_DEPARTEMENT = $value;
    }
}
