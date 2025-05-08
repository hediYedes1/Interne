<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Repondre;

#[ORM\Entity]
class Reponsecommentairepublication
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idreponsecommentaire;

    #[ORM\Column(type: "text")]
    private string $contenureponsecommentairepublication;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datereponsecommentairepublication;

    public function getIdreponsecommentaire()
    {
        return $this->idreponsecommentaire;
    }

    public function setIdreponsecommentaire($value)
    {
        $this->idreponsecommentaire = $value;
    }

    public function getContenureponsecommentairepublication()
    {
        return $this->contenureponsecommentairepublication;
    }

    public function setContenureponsecommentairepublication($value)
    {
        $this->contenureponsecommentairepublication = $value;
    }

    public function getDatereponsecommentairepublication()
    {
        return $this->datereponsecommentairepublication;
    }

    public function setDatereponsecommentairepublication($value)
    {
        $this->datereponsecommentairepublication = $value;
    }

    #[ORM\OneToMany(mappedBy: "idreponsecommentaire", targetEntity: Repondre::class)]
    private Collection $repondres;

        public function getRepondres(): Collection
        {
            return $this->repondres;
        }
    
        public function addRepondre(Repondre $repondre): self
        {
            if (!$this->repondres->contains($repondre)) {
                $this->repondres[] = $repondre;
                $repondre->setIdreponsecommentaire($this);
            }
    
            return $this;
        }
    
        public function removeRepondre(Repondre $repondre): self
        {
            if ($this->repondres->removeElement($repondre)) {
                // set the owning side to null (unless already changed)
                if ($repondre->getIdreponsecommentaire() === $this) {
                    $repondre->setIdreponsecommentaire(null);
                }
            }
    
            return $this;
        }
}
