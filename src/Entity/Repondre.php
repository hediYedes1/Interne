<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Repondre
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Reponse_commentaire_publication::class, inversedBy: "repondres")]
    #[ORM\JoinColumn(name: 'ID_REPONSE_COMMENTAIRE', referencedColumnName: 'ID_REPONSE_COMMENTAIRE', onDelete: 'CASCADE')]
    private Reponse_commentaire_publication $ID_REPONSE_COMMENTAIRE;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Commentaire_publication::class, inversedBy: "repondres")]
    #[ORM\JoinColumn(name: 'ID_COMMENTAIRE', referencedColumnName: 'ID_COMMENTAIRE', onDelete: 'CASCADE')]
    private Commentaire_publication $ID_COMMENTAIRE;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "repondres")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    public function getID_REPONSE_COMMENTAIRE()
    {
        return $this->ID_REPONSE_COMMENTAIRE;
    }

    public function setID_REPONSE_COMMENTAIRE($value)
    {
        $this->ID_REPONSE_COMMENTAIRE = $value;
    }

    public function getID_COMMENTAIRE()
    {
        return $this->ID_COMMENTAIRE;
    }

    public function setID_COMMENTAIRE($value)
    {
        $this->ID_COMMENTAIRE = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }
}
