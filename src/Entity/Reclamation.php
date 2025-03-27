<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Reponse_reclamation;

#[ORM\Entity]
class Reclamation
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_RECLAMATION;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "reclamations")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_RECLAMATION;

    #[ORM\Column(type: "text")]
    private string $TEXTE_RECLAMATION;

    public function getID_RECLAMATION()
    {
        return $this->ID_RECLAMATION;
    }

    public function setID_RECLAMATION($value)
    {
        $this->ID_RECLAMATION = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getDATE_RECLAMATION()
    {
        return $this->DATE_RECLAMATION;
    }

    public function setDATE_RECLAMATION($value)
    {
        $this->DATE_RECLAMATION = $value;
    }

    public function getTEXTE_RECLAMATION()
    {
        return $this->TEXTE_RECLAMATION;
    }

    public function setTEXTE_RECLAMATION($value)
    {
        $this->TEXTE_RECLAMATION = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_RECLAMATION", targetEntity: Reponse_reclamation::class)]
    private Collection $reponse_reclamations;

        public function getReponse_reclamations(): Collection
        {
            return $this->reponse_reclamations;
        }
    
        public function addReponse_reclamation(Reponse_reclamation $reponse_reclamation): self
        {
            if (!$this->reponse_reclamations->contains($reponse_reclamation)) {
                $this->reponse_reclamations[] = $reponse_reclamation;
                $reponse_reclamation->setID_RECLAMATION($this);
            }
    
            return $this;
        }
    
        public function removeReponse_reclamation(Reponse_reclamation $reponse_reclamation): self
        {
            if ($this->reponse_reclamations->removeElement($reponse_reclamation)) {
                // set the owning side to null (unless already changed)
                if ($reponse_reclamation->getID_RECLAMATION() === $this) {
                    $reponse_reclamation->setID_RECLAMATION(null);
                }
            }
    
            return $this;
        }
}
