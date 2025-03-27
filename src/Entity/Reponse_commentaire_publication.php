<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Repondre;

#[ORM\Entity]
class Reponse_commentaire_publication
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_REPONSE_COMMENTAIRE;

    #[ORM\Column(type: "text")]
    private string $CONTENU_REPONSE_COMMENTAIRE_PUBLICATION;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_REPONSE_COMMENTAIRE_PUBLICATION;

    public function getID_REPONSE_COMMENTAIRE()
    {
        return $this->ID_REPONSE_COMMENTAIRE;
    }

    public function setID_REPONSE_COMMENTAIRE($value)
    {
        $this->ID_REPONSE_COMMENTAIRE = $value;
    }

    public function getCONTENU_REPONSE_COMMENTAIRE_PUBLICATION()
    {
        return $this->CONTENU_REPONSE_COMMENTAIRE_PUBLICATION;
    }

    public function setCONTENU_REPONSE_COMMENTAIRE_PUBLICATION($value)
    {
        $this->CONTENU_REPONSE_COMMENTAIRE_PUBLICATION = $value;
    }

    public function getDATE_REPONSE_COMMENTAIRE_PUBLICATION()
    {
        return $this->DATE_REPONSE_COMMENTAIRE_PUBLICATION;
    }

    public function setDATE_REPONSE_COMMENTAIRE_PUBLICATION($value)
    {
        $this->DATE_REPONSE_COMMENTAIRE_PUBLICATION = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_REPONSE_COMMENTAIRE", targetEntity: Repondre::class)]
    private Collection $repondres;

        public function getRepondres(): Collection
        {
            return $this->repondres;
        }
    
        public function addRepondre(Repondre $repondre): self
        {
            if (!$this->repondres->contains($repondre)) {
                $this->repondres[] = $repondre;
                $repondre->setID_REPONSE_COMMENTAIRE($this);
            }
    
            return $this;
        }
    
        public function removeRepondre(Repondre $repondre): self
        {
            if ($this->repondres->removeElement($repondre)) {
                // set the owning side to null (unless already changed)
                if ($repondre->getID_REPONSE_COMMENTAIRE() === $this) {
                    $repondre->setID_REPONSE_COMMENTAIRE(null);
                }
            }
    
            return $this;
        }
}
