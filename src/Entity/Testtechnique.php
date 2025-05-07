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
    #[Assert\Length(min: 5, max: 255, minMessage: "La description doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(type: "string", length: 255)]
    private string $descriptiontesttechnique;

    #[Assert\NotBlank(message: "La durée est obligatoire.")]
    #[Assert\Positive(message: "La durée doit être un entier positif.")]
    #[ORM\Column(type: "integer")]
    private int $dureetesttechnique;

    
    #[ORM\Column(type: "statuttesttechnique" , options: ["default" => "ENATTENTE"])]
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
        $this->statuttesttechnique = StatutTestTechnique::ENATTENTE;
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

    public function getQuestions(): array
{
    if ($this->questions === null) {
        return [];
    }
    
    $decoded = json_decode($this->questions, true);
    
    // Validation du format
    foreach ($decoded as &$question) {
        if (!isset($question['correctAnswers'])) {
            $question['correctAnswers'] = [];
        }
        
        // Convertit les valeurs en booléens si nécessaire
        foreach ($question['correctAnswers'] as $key => $value) {
            if (is_string($value)) {
                $question['correctAnswers'][$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }
    }
    
    return $decoded;
}

public function setQuestions(?array $questions): self
{
    // Validation avant encodage
    if ($questions) {
        foreach ($questions as &$question) {
            if (!isset($question['correctAnswers'])) {
                $question['correctAnswers'] = [];
            }
        }
    }
    
    $this->questions = $questions ? json_encode($questions) : null;
    return $this;
}
}