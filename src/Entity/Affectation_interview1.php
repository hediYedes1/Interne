<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Affectation_interview1
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Interview::class, inversedBy: "affectation_interview1s")]
    #[ORM\JoinColumn(name: 'ID_INTERVIEW', referencedColumnName: 'ID_INTERVIEW', onDelete: 'CASCADE')]
    private Interview $ID_INTERVIEW;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "affectation_interview1s")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_AFFECTATION_INTERVIEW;

    public function getID_INTERVIEW()
    {
        return $this->ID_INTERVIEW;
    }

    public function setID_INTERVIEW($value)
    {
        $this->ID_INTERVIEW = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getDATE_AFFECTATION_INTERVIEW()
    {
        return $this->DATE_AFFECTATION_INTERVIEW;
    }

    public function setDATE_AFFECTATION_INTERVIEW($value)
    {
        $this->DATE_AFFECTATION_INTERVIEW = $value;
    }
}
