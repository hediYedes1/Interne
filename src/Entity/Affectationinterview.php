<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Affectationinterview
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Interview::class, inversedBy: "affectationinterviews")]
    #[ORM\JoinColumn(name: 'idinterview', referencedColumnName: 'idinterview', onDelete: 'CASCADE')]
    private Interview $idinterview;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "affectationinterviews")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $dateaffectationinterview;

    public function getIdinterview()
    {
        return $this->idinterview;
    }

    public function setIdinterview($value)
    {
        $this->idinterview = $value;
    }

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }

    public function getDateaffectationinterview()
    {
        return $this->dateaffectationinterview;
    }

    public function setDateaffectationinterview(\DateTimeInterface $date): void
    {
        $this->dateaffectationinterview = $date;
    }
    public function __construct()
    {
        $this->dateaffectationinterview = new \DateTimeImmutable();
    }
}
