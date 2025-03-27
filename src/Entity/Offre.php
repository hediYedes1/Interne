<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Entreprise;
use Doctrine\Common\Collections\Collection;
use App\Entity\Interview;

#[ORM\Entity]
class Offre
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_OFFRE;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "text")]
    private string $TITRE_OFFRE;

    #[ORM\Column(type: "text")]
    private string $DESCRIPTION_OFFRE;

    #[ORM\Column(type: "float")]
    private float $SALAIRE_OFFRE;

    #[ORM\Column(type: "text")]
    private string $LOCALISATION_OFFRE;

    #[ORM\Column(type: "text")]
    private string $EMAIL_RH;

        #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: 'ID_ENTREPRISE', referencedColumnName: 'ID_ENTREPRISE', onDelete: 'CASCADE')]
    private Entreprise $ID_ENTREPRISE;

    #[ORM\Column(type: "text")]
    private string $TYPE_CONTRAT;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_LIMITE;

    public function getID_OFFRE()
    {
        return $this->ID_OFFRE;
    }

    public function setID_OFFRE($value)
    {
        $this->ID_OFFRE = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getTITRE_OFFRE()
    {
        return $this->TITRE_OFFRE;
    }

    public function setTITRE_OFFRE($value)
    {
        $this->TITRE_OFFRE = $value;
    }

    public function getDESCRIPTION_OFFRE()
    {
        return $this->DESCRIPTION_OFFRE;
    }

    public function setDESCRIPTION_OFFRE($value)
    {
        $this->DESCRIPTION_OFFRE = $value;
    }

    public function getSALAIRE_OFFRE()
    {
        return $this->SALAIRE_OFFRE;
    }

    public function setSALAIRE_OFFRE($value)
    {
        $this->SALAIRE_OFFRE = $value;
    }

    public function getLOCALISATION_OFFRE()
    {
        return $this->LOCALISATION_OFFRE;
    }

    public function setLOCALISATION_OFFRE($value)
    {
        $this->LOCALISATION_OFFRE = $value;
    }

    public function getEMAIL_RH()
    {
        return $this->EMAIL_RH;
    }

    public function setEMAIL_RH($value)
    {
        $this->EMAIL_RH = $value;
    }

    public function getID_ENTREPRISE()
    {
        return $this->ID_ENTREPRISE;
    }

    public function setID_ENTREPRISE($value)
    {
        $this->ID_ENTREPRISE = $value;
    }

    public function getTYPE_CONTRAT()
    {
        return $this->TYPE_CONTRAT;
    }

    public function setTYPE_CONTRAT($value)
    {
        $this->TYPE_CONTRAT = $value;
    }

    public function getDATE_LIMITE()
    {
        return $this->DATE_LIMITE;
    }

    public function setDATE_LIMITE($value)
    {
        $this->DATE_LIMITE = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_OFFRE", targetEntity: Projet::class)]
    private Collection $projets;

    #[ORM\OneToMany(mappedBy: "ID_OFFRE", targetEntity: Interview::class)]
    private Collection $interviews;
}
