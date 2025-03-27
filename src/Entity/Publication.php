<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Commentaire_publication;

#[ORM\Entity]
class Publication
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_PUBLICATION;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "publications")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

    #[ORM\Column(type: "text")]
    private string $CONTENU;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $DATE_PUBLICATION;

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

    public function getDATE_PUBLICATION()
    {
        return $this->DATE_PUBLICATION;
    }

    public function setDATE_PUBLICATION($value)
    {
        $this->DATE_PUBLICATION = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_PUBLICATION", targetEntity: Commentaire_publication::class)]
    private Collection $commentaire_publications;

        public function getCommentaire_publications(): Collection
        {
            return $this->commentaire_publications;
        }
    
        public function addCommentaire_publication(Commentaire_publication $commentaire_publication): self
        {
            if (!$this->commentaire_publications->contains($commentaire_publication)) {
                $this->commentaire_publications[] = $commentaire_publication;
                $commentaire_publication->setID_PUBLICATION($this);
            }
    
            return $this;
        }
    
        public function removeCommentaire_publication(Commentaire_publication $commentaire_publication): self
        {
            if ($this->commentaire_publications->removeElement($commentaire_publication)) {
                // set the owning side to null (unless already changed)
                if ($commentaire_publication->getID_PUBLICATION() === $this) {
                    $commentaire_publication->setID_PUBLICATION(null);
                }
            }
    
            return $this;
        }
}
