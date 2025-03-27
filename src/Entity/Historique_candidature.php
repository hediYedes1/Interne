<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Candidature;

#[ORM\Entity]
class Historique_candidature
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_HISTORIQUE;

        #[ORM\ManyToOne(targetEntity: Candidature::class, inversedBy: "historique_candidatures")]
    #[ORM\JoinColumn(name: 'ID_CANDIDATURE', referencedColumnName: 'ID_CANDIDATURE', onDelete: 'CASCADE')]
    private Candidature $ID_CANDIDATURE;

    #[ORM\Column(type: "string")]
    private string $STATUT_AVANT;

    #[ORM\Column(type: "string")]
    private string $STATUT_APRES;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $DATE_MODIFICATION;

    public function getID_HISTORIQUE()
    {
        return $this->ID_HISTORIQUE;
    }

    public function setID_HISTORIQUE($value)
    {
        $this->ID_HISTORIQUE = $value;
    }

    public function getID_CANDIDATURE()
    {
        return $this->ID_CANDIDATURE;
    }

    public function setID_CANDIDATURE($value)
    {
        $this->ID_CANDIDATURE = $value;
    }

    public function getSTATUT_AVANT()
    {
        return $this->STATUT_AVANT;
    }

    public function setSTATUT_AVANT($value)
    {
        $this->STATUT_AVANT = $value;
    }

    public function getSTATUT_APRES()
    {
        return $this->STATUT_APRES;
    }

    public function setSTATUT_APRES($value)
    {
        $this->STATUT_APRES = $value;
    }

    public function getDATE_MODIFICATION()
    {
        return $this->DATE_MODIFICATION;
    }

    public function setDATE_MODIFICATION($value)
    {
        $this->DATE_MODIFICATION = $value;
    }
}
