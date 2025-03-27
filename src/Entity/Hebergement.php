<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Partenariat;
use Doctrine\Common\Collections\Collection;
use App\Entity\Affectation_hebergement;

#[ORM\Entity]
class Hebergement
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_HEBERGEMENT;

        #[ORM\ManyToOne(targetEntity: Partenariat::class, inversedBy: "hebergements")]
    #[ORM\JoinColumn(name: 'ID_PARTENARIAT', referencedColumnName: 'ID_PARTENARIAT', onDelete: 'CASCADE')]
    private Partenariat $ID_PARTENARIAT;

    #[ORM\Column(type: "string", length: 255)]
    private string $NOM_HEBERGEMENT;

    #[ORM\Column(type: "string", length: 255)]
    private string $ADRESSE_HEBERGEMENT;

    #[ORM\Column(type: "string")]
    private string $TYPE_HEBERGEMENT;

    #[ORM\Column(type: "text")]
    private string $DESCRIPTION_HEBERGEMENT;

    #[ORM\Column(type: "integer")]
    private int $NBR_NUITE_HEBERGEMENT;

    #[ORM\Column(type: "boolean")]
    private bool $DISPONIBILITE_HEBERGEMENT;

    #[ORM\Column(type: "string", length: 255)]
    private string $LOCALISATION_HEBERGEMENT;

    #[ORM\Column(type: "float")]
    private float $PRIX_HEBERGEMENT;

    public function getID_HEBERGEMENT()
    {
        return $this->ID_HEBERGEMENT;
    }

    public function setID_HEBERGEMENT($value)
    {
        $this->ID_HEBERGEMENT = $value;
    }

    public function getID_PARTENARIAT()
    {
        return $this->ID_PARTENARIAT;
    }

    public function setID_PARTENARIAT($value)
    {
        $this->ID_PARTENARIAT = $value;
    }

    public function getNOM_HEBERGEMENT()
    {
        return $this->NOM_HEBERGEMENT;
    }

    public function setNOM_HEBERGEMENT($value)
    {
        $this->NOM_HEBERGEMENT = $value;
    }

    public function getADRESSE_HEBERGEMENT()
    {
        return $this->ADRESSE_HEBERGEMENT;
    }

    public function setADRESSE_HEBERGEMENT($value)
    {
        $this->ADRESSE_HEBERGEMENT = $value;
    }

    public function getTYPE_HEBERGEMENT()
    {
        return $this->TYPE_HEBERGEMENT;
    }

    public function setTYPE_HEBERGEMENT($value)
    {
        $this->TYPE_HEBERGEMENT = $value;
    }

    public function getDESCRIPTION_HEBERGEMENT()
    {
        return $this->DESCRIPTION_HEBERGEMENT;
    }

    public function setDESCRIPTION_HEBERGEMENT($value)
    {
        $this->DESCRIPTION_HEBERGEMENT = $value;
    }

    public function getNBR_NUITE_HEBERGEMENT()
    {
        return $this->NBR_NUITE_HEBERGEMENT;
    }

    public function setNBR_NUITE_HEBERGEMENT($value)
    {
        $this->NBR_NUITE_HEBERGEMENT = $value;
    }

    public function getDISPONIBILITE_HEBERGEMENT()
    {
        return $this->DISPONIBILITE_HEBERGEMENT;
    }

    public function setDISPONIBILITE_HEBERGEMENT($value)
    {
        $this->DISPONIBILITE_HEBERGEMENT = $value;
    }

    public function getLOCALISATION_HEBERGEMENT()
    {
        return $this->LOCALISATION_HEBERGEMENT;
    }

    public function setLOCALISATION_HEBERGEMENT($value)
    {
        $this->LOCALISATION_HEBERGEMENT = $value;
    }

    public function getPRIX_HEBERGEMENT()
    {
        return $this->PRIX_HEBERGEMENT;
    }

    public function setPRIX_HEBERGEMENT($value)
    {
        $this->PRIX_HEBERGEMENT = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_HEBERGEMENT", targetEntity: Affectation_hebergement::class)]
    private Collection $affectation_hebergements;

        public function getAffectation_hebergements(): Collection
        {
            return $this->affectation_hebergements;
        }
    
        public function addAffectation_hebergement(Affectation_hebergement $affectation_hebergement): self
        {
            if (!$this->affectation_hebergements->contains($affectation_hebergement)) {
                $this->affectation_hebergements[] = $affectation_hebergement;
                $affectation_hebergement->setID_HEBERGEMENT($this);
            }
    
            return $this;
        }
    
        public function removeAffectation_hebergement(Affectation_hebergement $affectation_hebergement): self
        {
            if ($this->affectation_hebergements->removeElement($affectation_hebergement)) {
                // set the owning side to null (unless already changed)
                if ($affectation_hebergement->getID_HEBERGEMENT() === $this) {
                    $affectation_hebergement->setID_HEBERGEMENT(null);
                }
            }
    
            return $this;
        }
}
