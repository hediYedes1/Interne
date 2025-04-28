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
    private int $identreprise;

    #[ORM\Column(type: "text")]
    private string $nomentreprise;

    #[ORM\Column(type: "text")]
    private string $descriptionentreprise;

    #[ORM\Column(type: "string", length: 255)]
    private string $logoentreprise;

    #[ORM\Column(type: "text")]
    private string $urlentreprise;

    #[ORM\Column(type: "text")]
    private string $secteurentreprise;

    public function getIdentreprise()
    {
        return $this->identreprise;
    }

    public function setIdentreprise($value)
    {
        $this->identreprise = $value;
    }

    public function getNomentreprise()
    {
        return $this->nomentreprise;
    }

    public function setNomentreprise($value)
    {
        $this->nomentreprise = $value;
    }

    public function getDescriptionentreprise()
    {
        return $this->descriptionentreprise;
    }

    public function setDescriptionentreprise($value)
    {
        $this->descriptionentreprise = $value;
    }

    public function getLogoentreprise()
    {
        return $this->logoentreprise;
    }

    public function setLogoentreprise($value)
    {
        $this->logoentreprise = $value;
    }

    public function getUrlentreprise()
    {
        return $this->urlentreprise;
    }

    public function setUrlentreprise($value)
    {
        $this->urlentreprise = $value;
    }

    public function getSecteurentreprise()
    {
        return $this->secteurentreprise;
    }

    public function setSecteurentreprise($value)
    {
        $this->secteurentreprise = $value;
    }

    #[ORM\OneToMany(mappedBy: "identreprise", targetEntity: Departmententreprise::class)]
    private Collection $departmententreprises;

        public function getDepartmententreprises(): Collection
        {
            return $this->departmententreprises;
        }
    
        public function addDepartmententreprise(Departmententreprise $departmententreprise): self
        {
            if (!$this->departmententreprises->contains($departmententreprise)) {
                $this->departmententreprises[] = $departmententreprise;
                $departmententreprise->setIdentreprise($this);
            }
    
            return $this;
        }
    
        public function removeDepartmententreprise(Departmententreprise $departmententreprise): self
        {
            if ($this->departmententreprises->removeElement($departmententreprise)) {
                // set the owning side to null (unless already changed)
                if ($departmententreprise->getIdentreprise() === $this) {
                    $departmententreprise->setIdentreprise(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "identreprise", targetEntity: Brancheentreprise::class)]
    private Collection $brancheentreprises;

    #[ORM\OneToMany(mappedBy: "entreprise", targetEntity: Offre::class)]
    private Collection $offres;
}
