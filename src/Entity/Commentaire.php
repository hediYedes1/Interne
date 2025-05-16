<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Publication;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: \App\Repository\CommentaireRepository::class)]
#[ORM\Table(name: 'commentaire')]
#[ORM\Index(name: 'fk_publication', columns: ['id_publication'])]
class Commentaire
{
    public function __construct()
    {
        $this->dateCommentaire = new \DateTime();
        $this->reponses = new ArrayCollection();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id_commentaire', type: 'integer')]
    private int $idCommentaire;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $contenu;

    #[ORM\Column(name: 'date_commentaire', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $dateCommentaire;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0])]
    private ?int $likes = 0;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0])]
    private ?int $dislikes = 0;

    #[ORM\ManyToOne(targetEntity: Publication::class, inversedBy: "commentaires")]
    #[ORM\JoinColumn(name: 'id_publication', referencedColumnName: 'id_publication')]
    private Publication $idPublication;
    
    #[ORM\OneToMany(mappedBy: 'idCommentaire', targetEntity: Reponse::class, orphanRemoval: true)]
    private Collection $reponses;

    public function getIdCommentaire(): int
    {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(int $idCommentaire): void
    {
        $this->idCommentaire = $idCommentaire;
    }

    public function getIdPublication(): \App\Entity\Publication
    {
        return $this->idPublication;
    }

    public function setIdPublication(\App\Entity\Publication $idPublication): void
    {
        $this->idPublication = $idPublication;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(?int $dislikes): void
    {
        $this->dislikes = $dislikes;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): void
    {
        $this->likes = $likes;
    }

    public function getDateCommentaire(): \DateTime
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(\DateTime $dateCommentaire): void
    {
        $this->dateCommentaire = $dateCommentaire;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setIdCommentaire($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getIdCommentaire() === $this) {
                $reponse->setIdCommentaire(null);
            }
        }

        return $this;
    }

    public function getLikeDislikeRatio(): float
    {
        $likes = $this->getLikes();
        $dislikes = $this->getDislikes();

        // Avoid division by zero, return 0 if there are no likes or dislikes
        if ($likes + $dislikes === 0) {
            return 0;
        }

        return $likes / ($likes + $dislikes);
    }


}
