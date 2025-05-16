<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\PublicationRepository::class)]
#[ORM\Table(name: 'publication')]
class Publication
{

    public function __construct()
    {
        $this->datePublication = new \DateTime();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id_publication', type: 'integer')]
    private int $idPublication;

    #[ORM\Column(type: 'string', length: 255)]
    private string $titre;

    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\Column(name: 'date_publication', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $datePublication;

    #[ORM\Column(type: 'integer')]
    private int $rating;

    #[ORM\Column(name: 'image_path', type: 'string', length: 255, nullable: true)]
    private ?string $imagePath = null;
    
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', nullable: true)]
    private ?Utilisateur $idutilisateur = null;

    #[ORM\OneToMany(mappedBy: 'idPublication', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function setCommentaires(Collection $commentaires): void
    {
        $this->commentaires = $commentaires;
    }

    public function getIdPublication(): int
    {
        return $this->idPublication;
    }

    public function setIdPublication(int $idPublication): void
    {
        $this->idPublication = $idPublication;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getDatePublication(): \DateTime
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTime $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getIdutilisateur(): ?Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?Utilisateur $idutilisateur): self
    {
        $this->idutilisateur = $idutilisateur;
        
        return $this;
    }
}
