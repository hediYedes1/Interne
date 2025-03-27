<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Offre;

#[ORM\Entity]
class Entreprise
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_ENTREPRISE;

    #[ORM\Column(type: "text")]
    private string $NOM_ENTREPRISE;

    #[ORM\Column(type: "text")]
    private string $DESCRIPTION_ENTREPRISE;

    #[ORM\Column(type: "string")]
    private string $LOGO_ENTREPRISE;

    #[ORM\Column(type: "text")]
    private string $URL_ENTREPRISE;

    #[ORM\Column(type: "text")]
    private string $SECTEUR_ENTREPRISE;

    public function getID_ENTREPRISE()
    {
        return $this->ID_ENTREPRISE;
    }

    public function setID_ENTREPRISE($value)
    {
        $this->ID_ENTREPRISE = $value;
    }

    public function getNOM_ENTREPRISE()
    {
        return $this->NOM_ENTREPRISE;
    }

    public function setNOM_ENTREPRISE($value)
    {
        $this->NOM_ENTREPRISE = $value;
    }

    public function getDESCRIPTION_ENTREPRISE()
    {
        return $this->DESCRIPTION_ENTREPRISE;
    }

    public function setDESCRIPTION_ENTREPRISE($value)
    {
        $this->DESCRIPTION_ENTREPRISE = $value;
    }

    public function getLOGO_ENTREPRISE()
    {
        return $this->LOGO_ENTREPRISE;
    }

    public function setLOGO_ENTREPRISE($value)
    {
        $this->LOGO_ENTREPRISE = $value;
    }

    public function getURL_ENTREPRISE()
    {
        return $this->URL_ENTREPRISE;
    }

    public function setURL_ENTREPRISE($value)
    {
        $this->URL_ENTREPRISE = $value;
    }

    public function getSECTEUR_ENTREPRISE()
    {
        return $this->SECTEUR_ENTREPRISE;
    }

    public function setSECTEUR_ENTREPRISE($value)
    {
        $this->SECTEUR_ENTREPRISE = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_ENTREPRISE", targetEntity: Department_entreprise::class)]
    private Collection $department_entreprises;

        public function getDepartment_entreprises(): Collection
        {
            return $this->department_entreprises;
        }
    
        public function addDepartment_entreprise(Department_entreprise $department_entreprise): self
        {
            if (!$this->department_entreprises->contains($department_entreprise)) {
                $this->department_entreprises[] = $department_entreprise;
                $department_entreprise->setID_ENTREPRISE($this);
            }
    
            return $this;
        }
    
        public function removeDepartment_entreprise(Department_entreprise $department_entreprise): self
        {
            if ($this->department_entreprises->removeElement($department_entreprise)) {
                // set the owning side to null (unless already changed)
                if ($department_entreprise->getID_ENTREPRISE() === $this) {
                    $department_entreprise->setID_ENTREPRISE(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "ID_ENTREPRISE", targetEntity: Branche_entreprise::class)]
    private Collection $branche_entreprises;

    #[ORM\OneToMany(mappedBy: "ID_ENTREPRISE", targetEntity: Offre::class)]
    private Collection $offres;
}
