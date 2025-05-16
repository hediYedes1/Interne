<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'reponse_commentaire')]
class ReponseCommentaire
{
    public function __construct()
    {
        $this->dateReponse = new \DateTime();
        $this->likes = 0;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id_reponse', type: 'integer')]
    private int $idReponse;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $contenu;

    #[ORM\Column(name: 'date_reponse', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $dateReponse;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    private int $likes = 0;

    #[ORM\ManyToOne(targetEntity: Commentaire::class, inversedBy: 'reponses')]
    #[ORM\JoinColumn(name: 'id_commentaire', referencedColumnName: 'id_commentaire', nullable: false)]
    private Commentaire $commentaire;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'idutilisateur', nullable: false)]
    private Utilisateur $utilisateur;

    public function getIdReponse(): int
    {
        return $this->idReponse;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getDateReponse(): \DateTime
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTime $dateReponse): self
    {
        $this->dateReponse = $dateReponse;
        return $this;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;
        return $this;
    }

    public function incrementLikes(): self
    {
        $this->likes++;
        return $this;
    }

    public function decrementLikes(): self
    {
        if ($this->likes > 0) {
            $this->likes--;
        }
        return $this;
    }

    public function getCommentaire(): Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(Commentaire $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
} 