<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Repondre;
use App\Enum\Role;

#[ORM\Entity]
class Utilisateur
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idutilisateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $nomutilisateur;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenomutilisateur;

    #[ORM\Column(type: "integer")]
    private int $ageutilisateur;

    #[ORM\Column(type: "string", length: 255)]
    private string $emailutilisateur;

    #[ORM\Column(type: "string", length: 255)]
    private string $motdepasseutilisateur;

    #[ORM\Column(type: "string", enumType: Role::class)]
    private Role $role;

    #[ORM\Column(type: "string", length: 255)]
    private string $resettoken;

    #[ORM\Column(type: "string", length: 255)]
    private string $profilepictureurl;

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }

    public function getNomutilisateur()
    {
        return $this->nomutilisateur;
    }

    public function setNomutilisateur($value)
    {
        $this->nomutilisateur = $value;
    }

    public function getPrenomutilisateur()
    {
        return $this->prenomutilisateur;
    }

    public function setPrenomutilisateur($value)
    {
        $this->prenomutilisateur = $value;
    }

    public function getAgeutilisateur()
    {
        return $this->ageutilisateur;
    }

    public function setAgeutilisateur($value)
    {
        $this->ageutilisateur = $value;
    }

    public function getEmailutilisateur()
    {
        return $this->emailutilisateur;
    }

    public function setEmailutilisateur($value)
    {
        $this->emailutilisateur = $value;
    }

    public function getMotdepasseutilisateur()
    {
        return $this->motdepasseutilisateur;
    }

    public function setMotdepasseutilisateur($value)
    {
        $this->motdepasseutilisateur = $value;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getResettoken()
    {
        return $this->resettoken;
    }

    public function setResettoken($value)
    {
        $this->resettoken = $value;
    }

    public function getProfilepictureurl()
    {
        return $this->profilepictureurl;
    }

    public function setProfilepictureurl($value)
    {
        $this->profilepictureurl = $value;
    }

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Affectationhebergement::class)]
    private Collection $affectationhebergements;

        public function getAffectationhebergements(): Collection
        {
            return $this->affectationhebergements;
        }
    
        public function addAffectationhebergement(Affectationhebergement $affectationhebergement): self
        {
            if (!$this->affectationhebergements->contains($affectationhebergement)) {
                $this->affectationhebergements[] = $affectationhebergement;
                $affectationhebergement->setIdutilisateur($this);
            }
    
            return $this;
        }
    
        public function removeAffectationhebergement(Affectationhebergement $affectationhebergement): self
        {
            if ($this->affectationhebergements->removeElement($affectationhebergement)) {
                // set the owning side to null (unless already changed)
                if ($affectationhebergement->getIdutilisateur() === $this) {
                    $affectationhebergement->setIdutilisateur(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Affectationinterview::class)]
    private Collection $affectationinterviews;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Brancheentreprise::class)]
    private Collection $brancheentreprises;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Publication::class)]
    private Collection $publications;

  //  #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Commentairepublication::class)]
   // private Collection $commentairepublications;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Offre::class)]
    private Collection $offres;

    #[ORM\OneToMany(mappedBy: "idutilisateur", targetEntity: Repondre::class)]
    private Collection $repondres;
}
