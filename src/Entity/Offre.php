<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Entreprise;
use Doctrine\Common\Collections\Collection;
use App\Entity\Interview;

#[ORM\Entity]
class Offre
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idoffre;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

        #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "offres")]
    #[ORM\JoinColumn(name: 'identreprise', referencedColumnName: 'identreprise', onDelete: 'CASCADE')]
    private Entreprise $identreprise;

    #[ORM\Column(type: "text")]
    private string $titreoffre;

    #[ORM\Column(type: "text")]
    private string $descriptionoffre;

    #[ORM\Column(type: "float")]
    private float $salaireoffre;

    #[ORM\Column(type: "text")]
    private string $localisationoffre;

    #[ORM\Column(type: "text")]
    private string $emailrh;

    #[ORM\Column(type: "text")]
    private string $typecontrat;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datelimite;

    public function getIdoffre()
    {
        return $this->idoffre;
    }

    public function setIdoffre($value)
    {
        $this->idoffre = $value;
    }

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }

    public function getIdentreprise()
    {
        return $this->identreprise;
    }

    public function setIdentreprise($value)
    {
        $this->identreprise = $value;
    }

    public function getTitreoffre()
    {
        return $this->titreoffre;
    }

    public function setTitreoffre($value)
    {
        $this->titreoffre = $value;
    }

    public function getDescriptionoffre()
    {
        return $this->descriptionoffre;
    }

    public function setDescriptionoffre($value)
    {
        $this->descriptionoffre = $value;
    }

    public function getSalaireoffre()
    {
        return $this->salaireoffre;
    }

    public function setSalaireoffre($value)
    {
        $this->salaireoffre = $value;
    }

    public function getLocalisationoffre()
    {
        return $this->localisationoffre;
    }

    public function setLocalisationoffre($value)
    {
        $this->localisationoffre = $value;
    }

    public function getEmailrh()
    {
        return $this->emailrh;
    }

    public function setEmailrh($value)
    {
        $this->emailrh = $value;
    }

    public function getTypecontrat()
    {
        return $this->typecontrat;
    }

    public function setTypecontrat($value)
    {
        $this->typecontrat = $value;
    }

    public function getDatelimite()
    {
        return $this->datelimite;
    }

    public function setDatelimite($value)
    {
        $this->datelimite = $value;
    }

    #[ORM\OneToMany(mappedBy: "idoffre", targetEntity: Projet::class)]
    private Collection $projets;

    #[ORM\OneToMany(mappedBy: "idoffre", targetEntity: Interview::class)]
    private Collection $interviews;
}
