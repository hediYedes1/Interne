<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commentaire;

#[ORM\Entity(repositoryClass: \App\Repository\ReponseRepository::class)]
#[ORM\Table(name: 'reponse')]
#[ORM\Index(name: 'fk_commentaire', columns: ['id_commentaire'])]
class Reponse
{

    public function __construct()
    {
        $this->dateReponse = new \DateTime();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id_reponse', type: 'integer')]
    private int $idReponse;

    #[ORM\Column(name: 'contenu_reponse', type: 'text')]
    private string $contenuReponse;

    #[ORM\Column(name: 'date_reponse', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $dateReponse;

    #[ORM\ManyToOne(targetEntity: Commentaire::class)]
    #[ORM\JoinColumn(name: 'id_commentaire', referencedColumnName: 'id_commentaire')]
    private Commentaire $idCommentaire;

    public function getIdReponse(): int
    {
        return $this->idReponse;
    }

    public function setIdReponse(int $idReponse): void
    {
        $this->idReponse = $idReponse;
    }

    public function getContenuReponse(): string
    {
        return $this->contenuReponse;
    }

    public function setContenuReponse(string $contenuReponse): void
    {
        $this->contenuReponse = $contenuReponse;
    }

    public function getIdCommentaire(): \App\Entity\Commentaire
    {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(\App\Entity\Commentaire $idCommentaire): void
    {
        $this->idCommentaire = $idCommentaire;
    }

    public function getDateReponse(): \DateTime
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTime $dateReponse): void
    {
        $this->dateReponse = $dateReponse;
    }


}
