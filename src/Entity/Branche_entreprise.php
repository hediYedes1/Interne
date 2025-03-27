<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Partenariat;

#[ORM\Entity]
class Branche_entreprise
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_BRANCHE;

        #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "branche_entreprises")]
    #[ORM\JoinColumn(name: 'ID_ENTREPRISE', referencedColumnName: 'ID_ENTREPRISE', onDelete: 'CASCADE')]
    private Entreprise $ID_ENTREPRISE;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "branche_entreprises")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "text")]
    private string $LOCALISATION_BRANCHE;

    #[ORM\Column(type: "text")]
    private string $EMAIL_BRANCHE;

    #[ORM\Column(type: "string", length: 15)]
    private string $CONTACT_BRANCHE;

    #[ORM\Column(type: "integer")]
    private int $NOMBRE_EMPLOYE;

    #[ORM\Column(type: "text")]
    private string $RESPONSABLE_BRANCHE;

    public function getID_BRANCHE()
    {
        return $this->ID_BRANCHE;
    }

    public function setID_BRANCHE($value)
    {
        $this->ID_BRANCHE = $value;
    }

    public function getID_ENTREPRISE()
    {
        return $this->ID_ENTREPRISE;
    }

    public function setID_ENTREPRISE($value)
    {
        $this->ID_ENTREPRISE = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getLOCALISATION_BRANCHE()
    {
        return $this->LOCALISATION_BRANCHE;
    }

    public function setLOCALISATION_BRANCHE($value)
    {
        $this->LOCALISATION_BRANCHE = $value;
    }

    public function getEMAIL_BRANCHE()
    {
        return $this->EMAIL_BRANCHE;
    }

    public function setEMAIL_BRANCHE($value)
    {
        $this->EMAIL_BRANCHE = $value;
    }

    public function getCONTACT_BRANCHE()
    {
        return $this->CONTACT_BRANCHE;
    }

    public function setCONTACT_BRANCHE($value)
    {
        $this->CONTACT_BRANCHE = $value;
    }

    public function getNOMBRE_EMPLOYE()
    {
        return $this->NOMBRE_EMPLOYE;
    }

    public function setNOMBRE_EMPLOYE($value)
    {
        $this->NOMBRE_EMPLOYE = $value;
    }

    public function getRESPONSABLE_BRANCHE()
    {
        return $this->RESPONSABLE_BRANCHE;
    }

    public function setRESPONSABLE_BRANCHE($value)
    {
        $this->RESPONSABLE_BRANCHE = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_BRANCHE", targetEntity: Partenariat::class)]
    private Collection $partenariats;
}
