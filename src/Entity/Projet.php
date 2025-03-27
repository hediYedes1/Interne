<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Offre;

#[ORM\Entity]
class Projet
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_PROJET;

        #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: "projets")]
    #[ORM\JoinColumn(name: 'ID_OFFRE', referencedColumnName: 'ID_OFFRE', onDelete: 'CASCADE')]
    private Offre $ID_OFFRE;

    #[ORM\Column(type: "text")]
    private string $TITRE_PROJET;

    #[ORM\Column(type: "text")]
    private string $DESCRIPTION_PROJET;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_DEBUT;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_FIN;

    public function getID_PROJET()
    {
        return $this->ID_PROJET;
    }

    public function setID_PROJET($value)
    {
        $this->ID_PROJET = $value;
    }

    public function getID_OFFRE()
    {
        return $this->ID_OFFRE;
    }

    public function setID_OFFRE($value)
    {
        $this->ID_OFFRE = $value;
    }

    public function getTITRE_PROJET()
    {
        return $this->TITRE_PROJET;
    }

    public function setTITRE_PROJET($value)
    {
        $this->TITRE_PROJET = $value;
    }

    public function getDESCRIPTION_PROJET()
    {
        return $this->DESCRIPTION_PROJET;
    }

    public function setDESCRIPTION_PROJET($value)
    {
        $this->DESCRIPTION_PROJET = $value;
    }

    public function getDATE_DEBUT()
    {
        return $this->DATE_DEBUT;
    }

    public function setDATE_DEBUT($value)
    {
        $this->DATE_DEBUT = $value;
    }

    public function getDATE_FIN()
    {
        return $this->DATE_FIN;
    }

    public function setDATE_FIN($value)
    {
        $this->DATE_FIN = $value;
    }
}
