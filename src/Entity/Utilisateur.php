<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Repondre;

#[ORM\Entity]
class Utilisateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_UTILISATEUR;

    #[ORM\Column(type: "string", length: 100)]
    private string $NOM_UTILISATEUR;

    #[ORM\Column(type: "string", length: 100)]
    private string $PRENOM_UTILISATEUR;

    #[ORM\Column(type: "integer")]
    private int $AGE_UTILISATEUR;

    #[ORM\Column(type: "string", length: 255)]
    private string $EMAIL_UTILISATEUR;

    #[ORM\Column(type: "string", length: 255)]
    private string $MOT_DE_PASSE_UTILISATEUR;

    #[ORM\Column(type: "string")]
    private string $ROLE;

    #[ORM\Column(type: "string", length: 255)]
    private string $reset_token;

    #[ORM\Column(type: "string", length: 255)]
    private string $profile_picture_url;

    public function getID_UTILISATEUR()
    {
        return $this->ID_UTILISATEUR;
    }

    public function setID_UTILISATEUR($value)
    {
        $this->ID_UTILISATEUR = $value;
    }

    public function getNOM_UTILISATEUR()
    {
        return $this->NOM_UTILISATEUR;
    }

    public function setNOM_UTILISATEUR($value)
    {
        $this->NOM_UTILISATEUR = $value;
    }

    public function getPRENOM_UTILISATEUR()
    {
        return $this->PRENOM_UTILISATEUR;
    }

    public function setPRENOM_UTILISATEUR($value)
    {
        $this->PRENOM_UTILISATEUR = $value;
    }

    public function getAGE_UTILISATEUR()
    {
        return $this->AGE_UTILISATEUR;
    }

    public function setAGE_UTILISATEUR($value)
    {
        $this->AGE_UTILISATEUR = $value;
    }

    public function getEMAIL_UTILISATEUR()
    {
        return $this->EMAIL_UTILISATEUR;
    }

    public function setEMAIL_UTILISATEUR($value)
    {
        $this->EMAIL_UTILISATEUR = $value;
    }

    public function getMOT_DE_PASSE_UTILISATEUR()
    {
        return $this->MOT_DE_PASSE_UTILISATEUR;
    }

    public function setMOT_DE_PASSE_UTILISATEUR($value)
    {
        $this->MOT_DE_PASSE_UTILISATEUR = $value;
    }

    public function getROLE()
    {
        return $this->ROLE;
    }

    public function setROLE($value)
    {
        $this->ROLE = $value;
    }

    public function getReset_token()
    {
        return $this->reset_token;
    }

    public function setReset_token($value)
    {
        $this->reset_token = $value;
    }

    public function getProfile_picture_url()
    {
        return $this->profile_picture_url;
    }

    public function setProfile_picture_url($value)
    {
        $this->profile_picture_url = $value;
    }

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Candidature::class)]
    private Collection $candidatures;

        public function getCandidatures(): Collection
        {
            return $this->candidatures;
        }
    
        public function addCandidature(Candidature $candidature): self
        {
            if (!$this->candidatures->contains($candidature)) {
                $this->candidatures[] = $candidature;
                $candidature->setID_UTILISATEUR($this);
            }
    
            return $this;
        }
    
        public function removeCandidature(Candidature $candidature): self
        {
            if ($this->candidatures->removeElement($candidature)) {
                // set the owning side to null (unless already changed)
                if ($candidature->getID_UTILISATEUR() === $this) {
                    $candidature->setID_UTILISATEUR(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Reclamation::class)]
    private Collection $reclamations;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Affectation_hebergement::class)]
    private Collection $affectation_hebergements;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Affectation_interview::class)]
    private Collection $affectation_interviews;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Affectation_interview1::class)]
    private Collection $affectation_interview1s;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Branche_entreprise::class)]
    private Collection $branche_entreprises;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Interview::class)]
    private Collection $interviews;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Offre::class)]
    private Collection $offres;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Commentaire_publication::class)]
    private Collection $commentaire_publications;

    #[ORM\OneToMany(mappedBy: "ID_UTILISATEUR", targetEntity: Repondre::class)]
    private Collection $repondres;
}
