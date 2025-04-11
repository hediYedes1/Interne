<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Commentairepublication;

#[ORM\Entity]
class Publication
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idpublication;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "publications")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "text")]
    private string $contenu;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $datepublication;

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

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($value)
    {
        $this->contenu = $value;
    }

    public function getDatepublication()
    {
        return $this->datepublication;
    }

    public function setDatepublication($value)
    {
        $this->datepublication = $value;
    }

    #[ORM\OneToMany(mappedBy: "idpublication", targetEntity: Commentairepublication::class)]
    private Collection $commentairepublications;

        public function getCommentairepublications(): Collection
        {
            return $this->commentairepublications;
        }
    
        public function addCommentairepublication(Commentairepublication $commentairepublication): self
        {
            if (!$this->commentairepublications->contains($commentairepublication)) {
                $this->commentairepublications[] = $commentairepublication;
                $commentairepublication->setIdpublication($this);
            }
    
            return $this;
        }
    
        public function removeCommentairepublication(Commentairepublication $commentairepublication): self
        {
            if ($this->commentairepublications->removeElement($commentairepublication)) {
                // set the owning side to null (unless already changed)
                if ($commentairepublication->getIdpublication() === $this) {
                    $commentairepublication->setIdpublication(null);
                }
            }
    
            return $this;
        }
}
