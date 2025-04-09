<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Interview;
use App\Enum\StatutTestTechnique;
use App\Enum\TypeInterview;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity]
class Testtechnique
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idtesttechnique;

        #[ORM\ManyToOne(targetEntity: Interview::class, inversedBy: "testtechniques")]
    #[ORM\JoinColumn(name: 'idinterview', referencedColumnName: 'idinterview', onDelete: 'CASCADE')]
    private Interview $idinterview;

    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(min: 5, max: 255, minMessage: "Le titre doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(type: "string", length: 255)]
    private string $titretesttechnique;

    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[ORM\Column(type: "string", length: 255)]
    private string $descriptiontesttechnique;

    #[Assert\NotBlank(message: "La durée est obligatoire.")]
    #[Assert\Positive(message: "La durée doit être un entier positif.")]
    #[ORM\Column(type: "integer")]
    private int $dureetesttechnique;

    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[ORM\Column(type: "statuttesttechnique")]
    private StatutTestTechnique $statuttesttechnique;

    
    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $datecreationtesttechnique ;

    #[ORM\Column(type: 'text', nullable: true)]
private ?string $questions = null;

    public function getIdtesttechnique()
    {
        return $this->idtesttechnique;
    }

    public function setIdtesttechnique($value)
    {
        $this->idtesttechnique = $value;
    }

    public function getIdinterview()
    {
        return $this->idinterview;
    }

    public function setIdinterview($value)
    {
        $this->idinterview = $value;
    }

    public function getTitretesttechnique()
    {
        return $this->titretesttechnique;
    }

    public function setTitretesttechnique($value)
    {
        $this->titretesttechnique = $value;
    }

    public function getDescriptiontesttechnique()
    {
        return $this->descriptiontesttechnique;
    }

    public function setDescriptiontesttechnique($value)
    {
        $this->descriptiontesttechnique = $value;
    }

    public function getDureetesttechnique()
    {
        return $this->dureetesttechnique;
    }


    public function setDureetesttechnique($value)
    {
        $this->dureetesttechnique = $value;
    }

    public function getStatuttesttechnique(): StatutTestTechnique
    {
        return $this->statuttesttechnique;
    }

    public function setStatuttesttechnique(StatutTestTechnique $statuttesttechnique): self
    {
        $this->statuttesttechnique = $statuttesttechnique;
        return $this;
    }
    public function __construct()
    {
        $this->datecreationtesttechnique = new \DateTimeImmutable();
    }
    
    

    public function getDatecreationtesttechnique()
    {
        return $this->datecreationtesttechnique;
    }

    #[ORM\PrePersist]
    public function setDatecreationtesttechnique(): void
    {
        $this->datecreationtesttechnique = new \DateTimeImmutable();
    }

    public function getQuestions(): ?array
{
    return $this->questions ? json_decode($this->questions, true) : [];
}

public function setQuestions(?array $value): self
{
    $this->questions = $value ? json_encode($value) : null;
    return $this;
}
}
