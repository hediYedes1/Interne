<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Commentairepublication;
use Doctrine\Common\Collections\Collection;
use App\Entity\Repondre;

#[ORM\Entity]
class Commentairepublication
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idcommentaire;

        #[ORM\ManyToOne(targetEntity: Publication::class, inversedBy: "commentairepublications")]
    #[ORM\JoinColumn(name: 'idpublication', referencedColumnName: 'idpublication', onDelete: 'CASCADE')]
    private Publication $idpublication;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "commentairepublications")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

        #[ORM\ManyToOne(targetEntity: Commentairepublication::class, inversedBy: "commentairepublications")]
    #[ORM\JoinColumn(name: 'idreponse', referencedColumnName: 'idcommentaire', onDelete: 'CASCADE')]
    private Commentairepublication $idreponse;

    #[ORM\Column(type: "text")]
    private string $contenu;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $datecommentaire;

    public function getIdcommentaire()
    {
        return $this->idcommentaire;
    }

    public function setIdcommentaire($value)
    {
        $this->idcommentaire = $value;
    }

    public function getIdpublication()
    {
        return $this->idpublication;
    }

    public function setIdpublication($value)
    {
        $this->idpublication = $value;
    }

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }

    public function getIdreponse()
    {
        return $this->idreponse;
    }

    public function setIdreponse($value)
    {
        $this->idreponse = $value;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($value)
    {
        $this->contenu = $value;
    }

    public function getDatecommentaire()
    {
        return $this->datecommentaire;
    }

    public function setDatecommentaire($value)
    {
        $this->datecommentaire = $value;
    }

    #[ORM\OneToMany(mappedBy: "idreponse", targetEntity: Commentairepublication::class)]
    private Collection $commentairepublications;

    #[ORM\OneToMany(mappedBy: "idcommentaire", targetEntity: Repondre::class)]
    private Collection $repondres;

        public function getRepondres(): Collection
        {
            return $this->repondres;
        }
    
        public function addRepondre(Repondre $repondre): self
        {
            if (!$this->repondres->contains($repondre)) {
                $this->repondres[] = $repondre;
                $repondre->setIdcommentaire($this);
            }
    
            return $this;
        }
    
        public function removeRepondre(Repondre $repondre): self
        {
            if ($this->repondres->removeElement($repondre)) {
                // set the owning side to null (unless already changed)
                if ($repondre->getIdcommentaire() === $this) {
                    $repondre->setIdcommentaire(null);
                }
            }
    
            return $this;
        }
}
