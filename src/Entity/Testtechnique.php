<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Interview;
use App\Enum\StatutTestTechnique;
use App\Enum\TypeInterview;

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

    #[ORM\Column(type: "string", length: 255)]
    private string $titretesttechnique;

    #[ORM\Column(type: "string", length: 255)]
    private string $descriptiontesttechnique;

    #[ORM\Column(type: "integer")]
    private int $dureetesttechnique;

    #[ORM\Column(type: "statuttesttechnique")]
private StatutTestTechnique $statuttesttechnique;


    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $datecreationtesttechnique;

    #[ORM\Column(type: "text")]
    private string $questions;

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
    
    

    public function getDatecreationtesttechnique()
    {
        return $this->datecreationtesttechnique;
    }

    public function setDatecreationtesttechnique($value)
    {
        $this->datecreationtesttechnique = $value;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function setQuestions($value)
    {
        $this->questions = $value;
    }
}
