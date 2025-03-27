<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Offre;
use Doctrine\Common\Collections\Collection;
use App\Entity\Affectation_interview1;

#[ORM\Entity]
class Interview
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_INTERVIEW;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "interviews")]
    #[ORM\JoinColumn(name: 'ID_UTILISATEUR', referencedColumnName: 'ID_UTILISATEUR', onDelete: 'CASCADE')]
    private Utilisateur $ID_UTILISATEUR;

        #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: "interviews")]
    #[ORM\JoinColumn(name: 'ID_OFFRE', referencedColumnName: 'ID_OFFRE', onDelete: 'CASCADE')]
    private Offre $ID_OFFRE;

    #[ORM\Column(type: "text")]
    private string $titre_offre;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom_utilisateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenom_utilisateur;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $DATE_INTERVIEW;

    #[ORM\Column(type: "string")]
    private string $TYPE_INTERVIEW;

    #[ORM\Column(type: "string", length: 255)]
    private string $lien_Meet;

    #[ORM\Column(type: "string", length: 255)]
    private string $localisation;

    #[ORM\Column(type: "string")]
    private string $time_Interview;

    public function getID_INTERVIEW()
    {
        return $this->ID_INTERVIEW;
    }

    public function setID_INTERVIEW($value)
    {
        $this->ID_INTERVIEW = $value;
    }

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getID_OFFRE()
    {
        return $this->ID_OFFRE;
    }

    public function setID_OFFRE($value)
    {
        $this->ID_OFFRE = $value;
    }

    public function getTitre_offre()
    {
        return $this->titre_offre;
    }

    public function setTitre_offre($value)
    {
        $this->titre_offre = $value;
    }

    public function getNom_utilisateur()
    {
        return $this->nom_utilisateur;
    }

    public function setNom_utilisateur($value)
    {
        $this->nom_utilisateur = $value;
    }

    public function getPrenom_utilisateur()
    {
        return $this->prenom_utilisateur;
    }

    public function setPrenom_utilisateur($value)
    {
        $this->prenom_utilisateur = $value;
    }

    public function getDATE_INTERVIEW()
    {
        return $this->DATE_INTERVIEW;
    }

    public function setDATE_INTERVIEW($value)
    {
        $this->DATE_INTERVIEW = $value;
    }

    public function getTYPE_INTERVIEW()
    {
        return $this->TYPE_INTERVIEW;
    }

    public function setTYPE_INTERVIEW($value)
    {
        $this->TYPE_INTERVIEW = $value;
    }

    public function getLien_Meet()
    {
        return $this->lien_Meet;
    }

    public function setLien_Meet($value)
    {
        $this->lien_Meet = $value;
    }

    public function getLocalisation()
    {
        return $this->localisation;
    }

    public function setLocalisation($value)
    {
        $this->localisation = $value;
    }

    public function getTime_Interview()
    {
        return $this->time_Interview;
    }

    public function setTime_Interview($value)
    {
        $this->time_Interview = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_INTERVIEW", targetEntity: Test_technique::class)]
    private Collection $test_techniques;

    #[ORM\OneToMany(mappedBy: "ID_INTERVIEW", targetEntity: Affectation_interview::class)]
    private Collection $affectation_interviews;

    #[ORM\OneToMany(mappedBy: "ID_INTERVIEW", targetEntity: Affectation_interview1::class)]
    private Collection $affectation_interview1s;
}
