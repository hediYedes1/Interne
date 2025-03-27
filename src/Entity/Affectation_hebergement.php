<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Affectation_hebergement
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: "affectation_hebergements")]
    #[ORM\JoinColumn(name: 'ID_HEBERGEMENT', referencedColumnName: 'ID_HEBERGEMENT', onDelete: 'CASCADE')]
    private Hebergement $ID_HEBERGEMENT;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "affectation_hebergements")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_DEBUT;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_FIN;

    public function getID_HEBERGEMENT()
    {
        return $this->ID_HEBERGEMENT;
    }

    public function setID_HEBERGEMENT($value)
    {
        $this->ID_HEBERGEMENT = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
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
