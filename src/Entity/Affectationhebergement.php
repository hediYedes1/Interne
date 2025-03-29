<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Affectationhebergement
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: "affectationhebergements")]
    #[ORM\JoinColumn(name: 'idhebergement', referencedColumnName: 'idhebergement', onDelete: 'CASCADE')]
    private Hebergement $idhebergement;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "affectationhebergements")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datedebut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datefin;

    public function getIdhebergement()
    {
        return $this->idhebergement;
    }

    public function setIdhebergement($value)
    {
        $this->idhebergement = $value;
    }

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }

    public function getDatedebut()
    {
        return $this->datedebut;
    }

    public function setDatedebut($value)
    {
        $this->datedebut = $value;
    }

    public function getDatefin()
    {
        return $this->datefin;
    }

    public function setDatefin($value)
    {
        $this->datefin = $value;
    }
}
