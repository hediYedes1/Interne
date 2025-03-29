<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use App\Entity\Partenariat;

#[ORM\Entity]
class Brancheentreprise
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idbranche;

        #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "brancheentreprises")]
    #[ORM\JoinColumn(name: 'identreprise', referencedColumnName: 'identreprise', onDelete: 'CASCADE')]
    private Entreprise $identreprise;

        #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "brancheentreprises")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "text")]
    private string $localisationbranche;

    #[ORM\Column(type: "text")]
    private string $emailbranche;

    #[ORM\Column(type: "string", length: 15)]
    private string $contactbranche;

    #[ORM\Column(type: "integer")]
    private int $nombreemploye;

    #[ORM\Column(type: "text")]
    private string $responsablebranche;

    public function getIdbranche()
    {
        return $this->idbranche;
    }

    public function setIdbranche($value)
    {
        $this->idbranche = $value;
    }

    public function getIdentreprise()
    {
        return $this->identreprise;
    }

    public function setIdentreprise($value)
    {
        $this->identreprise = $value;
    }

    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur($value)
    {
        $this->idutilisateur = $value;
    }

    public function getLocalisationbranche()
    {
        return $this->localisationbranche;
    }

    public function setLocalisationbranche($value)
    {
        $this->localisationbranche = $value;
    }

    public function getEmailbranche()
    {
        return $this->emailbranche;
    }

    public function setEmailbranche($value)
    {
        $this->emailbranche = $value;
    }

    public function getContactbranche()
    {
        return $this->contactbranche;
    }

    public function setContactbranche($value)
    {
        $this->contactbranche = $value;
    }

    public function getNombreemploye()
    {
        return $this->nombreemploye;
    }

    public function setNombreemploye($value)
    {
        $this->nombreemploye = $value;
    }

    public function getResponsablebranche()
    {
        return $this->responsablebranche;
    }

    public function setResponsablebranche($value)
    {
        $this->responsablebranche = $value;
    }

    #[ORM\OneToMany(mappedBy: "idbranche", targetEntity: Partenariat::class)]
    private Collection $partenariats;

        public function getPartenariats(): Collection
        {
            return $this->partenariats;
        }
    
        public function addPartenariat(Partenariat $partenariat): self
        {
            if (!$this->partenariats->contains($partenariat)) {
                $this->partenariats[] = $partenariat;
                $partenariat->setIdbranche($this);
            }
    
            return $this;
        }
    
        public function removePartenariat(Partenariat $partenariat): self
        {
            if ($this->partenariats->removeElement($partenariat)) {
                // set the owning side to null (unless already changed)
                if ($partenariat->getIdbranche() === $this) {
                    $partenariat->setIdbranche(null);
                }
            }
    
            return $this;
        }
}
