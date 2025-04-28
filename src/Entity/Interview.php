<?php

namespace App\Entity;

use App\Doctrine\TypeInterviewType;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Offre;
use Doctrine\Common\Collections\Collection;
use App\Entity\Affectationinterview;
use App\Enum\TypeInterview;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
#[ORM\Entity]
class Interview
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idinterview;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: "interviews")]
    #[ORM\JoinColumn(name: 'idoffre', referencedColumnName: 'idoffre', onDelete: 'CASCADE')]
    private Offre $offre;

 
    #[ORM\Column(type: "text")]
    private string $titreoffre;

    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date doit être au moins aujourd'hui.")]
    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateinterview;

    #[Assert\NotBlank(message: "Le type d'interview est obligatoire.")]
    #[ORM\Column(type: "typeinterview")]
    private TypeInterview $typeinterview;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private string $lienmeet;

    #[Assert\NotBlank(message: "La localisation est obligatoire.")]
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private string $localisation;

    #[Assert\NotBlank(message: "L'heure est obligatoire.")]
    #[Assert\Callback([self::class, 'validateTimeInterview'])]
    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $timeinterview;

    public function getIdinterview()
    {
        return $this->idinterview;
    }
    public function __construct()
    {
        $this->testtechniques = new ArrayCollection();
        $this->affectationinterviews = new ArrayCollection();
    }

    public function setIdinterview($value)
    {
        $this->idinterview = $value;
    }

    public function getoffre()
    {
        return $this->offre;
    }

    public function setoffre($value)
    {
        $this->offre = $value;
    }

    public function getTitreoffre()
    {
        return $this->titreoffre;
    }

    public function setTitreoffre($value)
    {
        $this->titreoffre = $value;
    }

    public function getDateinterview()
    {
        return $this->dateinterview;
    }

    public function setDateinterview($value)
    {
        $this->dateinterview = $value;
    }

    public function getTypeinterview(): TypeInterview
    {
        return $this->typeinterview;
    }
    
    public function setTypeinterview(TypeInterview $typeinterview): self
    {
        $this->typeinterview = $typeinterview;
        return $this;
    }

    public function getLienmeet()
    {
        return $this->lienmeet;
    }

    public function setLienmeet($value)
    {
        $this->lienmeet = $value;
    }

    public function getLocalisation()
    {
        return $this->localisation;
    }

    public function setLocalisation($value)
    {
        $this->localisation = $value;
    }

    public function getTimeinterview()
    {
        return $this->timeinterview;
    }

    public function setTimeinterview($value)
    {
        $this->timeinterview = $value;
    }
    public function getTesttechniques(): Collection
    {
        return $this->testtechniques;
    }
    public function getAffectationinterviews(): Collection
    {
        return $this->affectationinterviews;
    }
    public static function validateTimeInterview($timeinterview, ExecutionContextInterface $context)
    {
        $now = new \DateTime();
        
        // Vérifie si l'heure est dans le passé (le même jour)
        if ($timeinterview->format('Y-m-d') == $now->format('Y-m-d') && $timeinterview->format('H:i') < $now->format('H:i')) {
            $context->buildViolation("L'heure doit être au moins l'heure actuelle.")
                ->atPath('timeinterview')
                ->addViolation();
        }
    }
    #[ORM\OneToMany(mappedBy: "idinterview", targetEntity: Testtechnique::class)]
    private Collection $testtechniques;

    #[ORM\OneToMany(mappedBy: "idinterview", targetEntity: Affectationinterview::class)]
    private Collection $affectationinterviews;
}
