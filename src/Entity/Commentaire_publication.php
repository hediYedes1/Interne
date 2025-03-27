<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Commentaire_publication;
use Doctrine\Common\Collections\Collection;
use App\Entity\Repondre;

#[ORM\Entity]
class Commentaire_publication
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_COMMENTAIRE;

        #[ORM\ManyToOne(targetEntity: Publication::class, inversedBy: "commentaire_publications")]
    #[ORM\JoinColumn(name: 'ID_PUBLICATION', referencedColumnName: 'ID_PUBLICATION', onDelete: 'CASCADE')]
    private Publication $ID_PUBLICATION;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "commentaire_publications")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "text")]
    private string $CONTENU;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $DATE_COMMENTAIRE;

        #[ORM\ManyToOne(targetEntity: Commentaire_publication::class, inversedBy: "commentaire_publications")]
    #[ORM\JoinColumn(name: 'ID_REPONSE', referencedColumnName: 'ID_COMMENTAIRE', onDelete: 'CASCADE')]
    private Commentaire_publication $ID_REPONSE;

    public function getID_COMMENTAIRE()
    {
        return $this->ID_COMMENTAIRE;
    }

    public function setID_COMMENTAIRE($value)
    {
        $this->ID_COMMENTAIRE = $value;
    }

    public function getID_PUBLICATION()
    {
        return $this->ID_PUBLICATION;
    }

    public function setID_PUBLICATION($value)
    {
        $this->ID_PUBLICATION = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getCONTENU()
    {
        return $this->CONTENU;
    }

    public function setCONTENU($value)
    {
        $this->CONTENU = $value;
    }

    public function getDATE_COMMENTAIRE()
    {
        return $this->DATE_COMMENTAIRE;
    }

    public function setDATE_COMMENTAIRE($value)
    {
        $this->DATE_COMMENTAIRE = $value;
    }

    public function getID_REPONSE()
    {
        return $this->ID_REPONSE;
    }

    public function setID_REPONSE($value)
    {
        $this->ID_REPONSE = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_REPONSE", targetEntity: Commentaire_publication::class)]
    private Collection $commentaire_publications;

    #[ORM\OneToMany(mappedBy: "ID_COMMENTAIRE", targetEntity: Repondre::class)]
    private Collection $repondres;

        public function getRepondres(): Collection
        {
            return $this->repondres;
        }
    
        public function addRepondre(Repondre $repondre): self
        {
            if (!$this->repondres->contains($repondre)) {
                $this->repondres[] = $repondre;
                $repondre->setID_COMMENTAIRE($this);
            }
    
            return $this;
        }
    
        public function removeRepondre(Repondre $repondre): self
        {
            if ($this->repondres->removeElement($repondre)) {
                // set the owning side to null (unless already changed)
                if ($repondre->getID_COMMENTAIRE() === $this) {
                    $repondre->setID_COMMENTAIRE(null);
                }
            }
    
            return $this;
        }
}
