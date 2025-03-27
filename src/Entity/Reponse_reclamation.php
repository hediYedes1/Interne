<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Reclamation;

#[ORM\Entity]
class Reponse_reclamation
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_REPONSE;

        #[ORM\ManyToOne(targetEntity: Reclamation::class, inversedBy: "reponse_reclamations")]
    #[ORM\JoinColumn(name: 'ID_RECLAMATION', referencedColumnName: 'ID_RECLAMATION', onDelete: 'CASCADE')]
    private Reclamation $ID_RECLAMATION;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_REPONSE;

    #[ORM\Column(type: "text")]
    private string $TEXTE_REPONSE;

    public function getID_REPONSE()
    {
        return $this->ID_REPONSE;
    }

    public function setID_REPONSE($value)
    {
        $this->ID_REPONSE = $value;
    }

    public function getID_RECLAMATION()
    {
        return $this->ID_RECLAMATION;
    }

    public function setID_RECLAMATION($value)
    {
        $this->ID_RECLAMATION = $value;
    }

    public function getDATE_REPONSE()
    {
        return $this->DATE_REPONSE;
    }

    public function setDATE_REPONSE($value)
    {
        $this->DATE_REPONSE = $value;
    }

    public function getTEXTE_REPONSE()
    {
        return $this->TEXTE_REPONSE;
    }

    public function setTEXTE_REPONSE($value)
    {
        $this->TEXTE_REPONSE = $value;
    }
}
