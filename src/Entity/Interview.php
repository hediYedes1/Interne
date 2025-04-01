<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Offre;
use Doctrine\Common\Collections\Collection;
use App\Entity\Affectationinterview;
use App\Enum\TypeInterview;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
class Interview
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idinterview;

        #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: "interviews")]
    #[ORM\JoinColumn(name: 'idoffre', referencedColumnName: 'idoffre', onDelete: 'CASCADE')]
    private Offre $idoffre;

    #[ORM\Column(type: "text")]
    private string $titreoffre;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateinterview;

    #[ORM\Column(type: "string", length: 20, enumType: TypeInterview::class)]
    private TypeInterview $typeinterview;

    #[ORM\Column(type: "string", length: 255)]
    private string $lienmeet;

    #[ORM\Column(type: "string", length: 255)]
    private string $localisation;

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

    public function getIdoffre()
    {
        return $this->idoffre;
    }

    public function setIdoffre($value)
    {
        $this->idoffre = $value;
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

    public function getTypeinterview(): string|TypeInterview
    {
        return $this->typeinterview;
    }

    public function setTypeinterview(string|TypeInterview $typeinterview): self
    {
        if (is_string($typeinterview)) {
            $this->typeinterview = TypeInterview::from($typeinterview);
        } else {
            $this->typeinterview = $typeinterview;
        }
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

    #[ORM\OneToMany(mappedBy: "idinterview", targetEntity: Testtechnique::class)]
    private Collection $testtechniques;

    #[ORM\OneToMany(mappedBy: "idinterview", targetEntity: Affectationinterview::class)]
    private Collection $affectationinterviews;
}
