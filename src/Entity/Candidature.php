<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Historique_candidature;

#[ORM\Entity]
class Candidature
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_CANDIDATURE;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "candidatures")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_CANDIDATURE;

    #[ORM\Column(type: "string")]
    private string $STATUT_CANDIDATURE;

    #[ORM\Column(type: "string", length: 65535)]
    private string $CV;

    public function getID_CANDIDATURE()
    {
        return $this->ID_CANDIDATURE;
    }

    public function setID_CANDIDATURE($value)
    {
        $this->ID_CANDIDATURE = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getDATE_CANDIDATURE()
    {
        return $this->DATE_CANDIDATURE;
    }

    public function setDATE_CANDIDATURE($value)
    {
        $this->DATE_CANDIDATURE = $value;
    }

    public function getSTATUT_CANDIDATURE()
    {
        return $this->STATUT_CANDIDATURE;
    }

    public function setSTATUT_CANDIDATURE($value)
    {
        $this->STATUT_CANDIDATURE = $value;
    }

    public function getCV()
    {
        return $this->CV;
    }

    public function setCV($value)
    {
        $this->CV = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_CANDIDATURE", targetEntity: Historique_candidature::class)]
    private Collection $historique_candidatures;

        public function getHistorique_candidatures(): Collection
        {
            return $this->historique_candidatures;
        }
    
        public function addHistorique_candidature(Historique_candidature $historique_candidature): self
        {
            if (!$this->historique_candidatures->contains($historique_candidature)) {
                $this->historique_candidatures[] = $historique_candidature;
                $historique_candidature->setID_CANDIDATURE($this);
            }
    
            return $this;
        }
    
        public function removeHistorique_candidature(Historique_candidature $historique_candidature): self
        {
            if ($this->historique_candidatures->removeElement($historique_candidature)) {
                // set the owning side to null (unless already changed)
                if ($historique_candidature->getID_CANDIDATURE() === $this) {
                    $historique_candidature->setID_CANDIDATURE(null);
                }
            }
    
            return $this;
        }
}
