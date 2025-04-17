<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Publication;

#[ORM\Entity]
#[ORM\Table(name: 'commentaire')]
#[ORM\Index(name: 'fk_publication', columns: ['id_publication'])]
class Commentaire
{
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

    #[ORM\ManyToOne(targetEntity: Publication::class)]
    #[ORM\JoinColumn(name: 'id_publication', referencedColumnName: 'id_publication')]
    private Publication $idPublication;

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


}
