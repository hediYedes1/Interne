<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Branche_entreprise;
use Doctrine\Common\Collections\Collection;
use App\Entity\Hebergement;

#[ORM\Entity]
class Partenariat
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_PARTENARIAT;

    #[ORM\Column(type: "string", length: 255)]
    private string $NOM_PARTENARIAT;

    #[ORM\Column(type: "string", length: 255)]
    private string $ADRESSE_PARTENARIAT;

        #[ORM\ManyToOne(targetEntity: Branche_entreprise::class, inversedBy: "partenariats")]
    #[ORM\JoinColumn(name: 'ID_BRANCHE', referencedColumnName: 'ID_BRANCHE', onDelete: 'CASCADE')]
    private Branche_entreprise $ID_BRANCHE;

    #[ORM\Column(type: "string", length: 15)]
    private string $NUM_TEL_PARTENARIAT;

    public function getID_PARTENARIAT()
    {
        return $this->ID_PARTENARIAT;
    }

    public function setID_PARTENARIAT($value)
    {
        $this->ID_PARTENARIAT = $value;
    }

    public function getNOM_PARTENARIAT()
    {
        return $this->NOM_PARTENARIAT;
    }

    public function setNOM_PARTENARIAT($value)
    {
        $this->NOM_PARTENARIAT = $value;
    }

    public function getADRESSE_PARTENARIAT()
    {
        return $this->ADRESSE_PARTENARIAT;
    }

    public function setADRESSE_PARTENARIAT($value)
    {
        $this->ADRESSE_PARTENARIAT = $value;
    }

    public function getID_BRANCHE()
    {
        return $this->ID_BRANCHE;
    }

    public function setID_BRANCHE($value)
    {
        $this->ID_BRANCHE = $value;
    }

    public function getNUM_TEL_PARTENARIAT()
    {
        return $this->NUM_TEL_PARTENARIAT;
    }

    public function setNUM_TEL_PARTENARIAT($value)
    {
        $this->NUM_TEL_PARTENARIAT = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_PARTENARIAT", targetEntity: Hebergement::class)]
    private Collection $hebergements;
}
