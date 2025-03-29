<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Repondre
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Reponsecommentairepublication::class, inversedBy: "repondres")]
    #[ORM\JoinColumn(name: 'idreponsecommentaire', referencedColumnName: 'idreponsecommentaire', onDelete: 'CASCADE')]
    private Reponsecommentairepublication $idreponsecommentaire;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Commentairepublication::class, inversedBy: "repondres")]
    #[ORM\JoinColumn(name: 'idcommentaire', referencedColumnName: 'idcommentaire', onDelete: 'CASCADE')]
    private Commentairepublication $idcommentaire;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "repondres")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    public function getIdreponsecommentaire()
    {
        return $this->idreponsecommentaire;
    }

    public function setIdreponsecommentaire($value)
    {
        $this->idreponsecommentaire = $value;
    }

    public function getIdcommentaire()
    {
        return $this->idcommentaire;
    }

    public function setIdcommentaire($value)
    {
        $this->idcommentaire = $value;
    }

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }
}
