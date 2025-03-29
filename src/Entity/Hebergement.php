<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Partenariat;
use Doctrine\Common\Collections\Collection;
use App\Entity\Affectationhebergement;
use App\Enum\TypeHebergement;

#[ORM\Entity]
class Hebergement
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idhebergement;

        #[ORM\ManyToOne(targetEntity: Partenariat::class, inversedBy: "hebergements")]
    #[ORM\JoinColumn(name: 'idpartenariat', referencedColumnName: 'idpartenariat', onDelete: 'CASCADE')]
    private Partenariat $idpartenariat;

    #[ORM\Column(type: "string", length: 255)]
    private string $nomhebergement;

    #[ORM\Column(type: "string", length: 255)]
    private string $adressehebergement;

    #[ORM\Column(type: "string", enumType: TypeHebergement::class)]
    private TypeHebergement $typehebergement;

    #[ORM\Column(type: "text")]
    private string $descriptionhebergement;

    #[ORM\Column(type: "integer")]
    private int $nbrnuitehebergement;

    #[ORM\Column(type: "boolean")]
    private bool $disponibilitehebergement;

    #[ORM\Column(type: "string", length: 255)]
    private string $localisationhebergement;

    #[ORM\Column(type: "float")]
    private float $prixhebergement;

    public function getIdhebergement()
    {
        return $this->idhebergement;
    }

    public function setIdhebergement($value)
    {
        $this->idhebergement = $value;
    }

    public function getIdpartenariat()
    {
        return $this->idpartenariat;
    }

    public function setIdpartenariat($value)
    {
        $this->idpartenariat = $value;
    }

    public function getNomhebergement()
    {
        return $this->nomhebergement;
    }

    public function setNomhebergement($value)
    {
        $this->nomhebergement = $value;
    }

    public function getAdressehebergement()
    {
        return $this->adressehebergement;
    }

    public function setAdressehebergement($value)
    {
        $this->adressehebergement = $value;
    }

    public function getTypehebergement()
    {
        return $this->typehebergement;
    }

    public function setTypehebergement($value)
    {
        $this->typehebergement = $value;
    }

    public function getDescriptionhebergement()
    {
        return $this->descriptionhebergement;
    }

    public function setDescriptionhebergement($value)
    {
        $this->descriptionhebergement = $value;
    }

    public function getNbrnuitehebergement()
    {
        return $this->nbrnuitehebergement;
    }

    public function setNbrnuitehebergement($value)
    {
        $this->nbrnuitehebergement = $value;
    }

    public function getDisponibilitehebergement()
    {
        return $this->disponibilitehebergement;
    }

    public function setDisponibilitehebergement($value)
    {
        $this->disponibilitehebergement = $value;
    }

    public function getLocalisationhebergement()
    {
        return $this->localisationhebergement;
    }

    public function setLocalisationhebergement($value)
    {
        $this->localisationhebergement = $value;
    }

    public function getPrixhebergement()
    {
        return $this->prixhebergement;
    }

    public function setPrixhebergement($value)
    {
        $this->prixhebergement = $value;
    }

    #[ORM\OneToMany(mappedBy: "idhebergement", targetEntity: Affectationhebergement::class)]
    private Collection $affectationhebergements;
}
