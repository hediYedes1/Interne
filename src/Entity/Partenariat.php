<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Brancheentreprise;
use Doctrine\Common\Collections\Collection;
use App\Entity\Hebergement;

#[ORM\Entity]
class Partenariat
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idpartenariat;

        #[ORM\ManyToOne(targetEntity: Brancheentreprise::class, inversedBy: "partenariats")]
    #[ORM\JoinColumn(name: 'idbranche', referencedColumnName: 'idbranche', onDelete: 'CASCADE')]
    private Brancheentreprise $idbranche;

    #[ORM\Column(type: "string", length: 255)]
    private string $nompartenariat;

    #[ORM\Column(type: "string", length: 255)]
    private string $adressepartenariat;

    #[ORM\Column(type: "string", length: 15)]
    private string $numtelpartenariat;

    public function getIdpartenariat()
    {
        return $this->idpartenariat;
    }

    public function setIdpartenariat($value)
    {
        $this->idpartenariat = $value;
    }

    public function getIdbranche()
    {
        return $this->idbranche;
    }

    public function setIdbranche($value)
    {
        $this->idbranche = $value;
    }

    public function getNompartenariat()
    {
        return $this->nompartenariat;
    }

    public function setNompartenariat($value)
    {
        $this->nompartenariat = $value;
    }

    public function getAdressepartenariat()
    {
        return $this->adressepartenariat;
    }

    public function setAdressepartenariat($value)
    {
        $this->adressepartenariat = $value;
    }

    public function getNumtelpartenariat()
    {
        return $this->numtelpartenariat;
    }

    public function setNumtelpartenariat($value)
    {
        $this->numtelpartenariat = $value;
    }

    #[ORM\OneToMany(mappedBy: "idpartenariat", targetEntity: Hebergement::class)]
    private Collection $hebergements;
}
