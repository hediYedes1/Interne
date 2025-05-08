<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Brancheentreprise;
use Doctrine\Common\Collections\Collection;
use App\Entity\Hebergement;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Partenariat
{
    #[ORM\Id]
    #[ORM\GeneratedValue] 
    #[ORM\Column(type: "integer")]
    private int $idpartenariat;

    #[ORM\ManyToOne(targetEntity: Brancheentreprise::class, inversedBy: "partenariats")]
    #[ORM\JoinColumn(name: 'idbranche', referencedColumnName: 'idbranche', onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "La branche est obligatoire.")]
    private Brancheentreprise $idbranche;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom du partenariat est requis.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $nompartenariat;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $adressepartenariat;

    #[ORM\Column(type: "string", length: 15)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est requis.")]
    #[Assert\Length(
        min: 8,
        max: 15,
        minMessage: "Le numéro doit contenir au moins {{ limit }} chiffres.",
        maxMessage: "Le numéro ne peut pas dépasser {{ limit }} chiffres."
    )]
    #[Assert\Regex(
        pattern: "/^\+?[0-9 ]+$/",
        message: "Le numéro de téléphone n'est pas valide."
    )]
    private string $numtelpartenariat;

    #[ORM\OneToMany(mappedBy: "idpartenariat", targetEntity: Hebergement::class)]
    private Collection $hebergements;

    public function getId(): ?int
    {
        return $this->idpartenariat;
    }

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

    public function __toString(): string
    {
        return $this->nompartenariat . ' (Branche : ' . $this->idbranche?->getIdentreprise() . ')';
    }
}
