<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Offre;

#[ORM\Entity]
class Projet
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idprojet;

        #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: "projets")]
    #[ORM\JoinColumn(name: 'idoffre', referencedColumnName: 'idoffre', onDelete: 'CASCADE')]
    private Offre $idoffre;

    #[ORM\Column(type: "text")]
    private string $titreprojet;

    #[ORM\Column(type: "text")]
    private string $descriptionprojet;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datedebut;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datefin;

    public function getIdprojet()
    {
        return $this->idprojet;
    }

    public function setIdprojet($value)
    {
        $this->idprojet = $value;
    }

    public function getIdoffre()
    {
        return $this->idoffre;
    }

    public function setIdoffre($value)
    {
        $this->idoffre = $value;
    }

    public function getTitreprojet()
    {
        return $this->titreprojet;
    }

    public function setTitreprojet($value)
    {
        $this->titreprojet = $value;
    }

    public function getDescriptionprojet()
    {
        return $this->descriptionprojet;
    }

    public function setDescriptionprojet($value)
    {
        $this->descriptionprojet = $value;
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
