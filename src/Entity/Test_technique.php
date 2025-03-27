<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Interview;

#[ORM\Entity]
class Test_technique
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $ID_TEST_TECHNIQUE;

        #[ORM\ManyToOne(targetEntity: Interview::class, inversedBy: "test_techniques")]
    #[ORM\JoinColumn(name: 'ID_INTERVIEW', referencedColumnName: 'ID_INTERVIEW', onDelete: 'CASCADE')]
    private Interview $ID_INTERVIEW;

    #[ORM\Column(type: "string", length: 255)]
    private string $TITRE_TEST_TECHNIQUE;

    #[ORM\Column(type: "string", length: 255)]
    private string $DESCRIPTION_TEST_TECHNIQUE;

    #[ORM\Column(type: "integer")]
    private int $DUREE_TEST_TECHNIQUE;

    #[ORM\Column(type: "string")]
    private string $STATUT_TEST_TECHNIQUE;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $DATE_CREATION_TEST_TECHNIQUE;

    #[ORM\Column(type: "text")]
    private string $questions;

    public function getID_TEST_TECHNIQUE()
    {
        return $this->ID_TEST_TECHNIQUE;
    }

    public function setID_TEST_TECHNIQUE($value)
    {
        $this->ID_TEST_TECHNIQUE = $value;
    }

    public function getID_INTERVIEW()
    {
        return $this->ID_INTERVIEW;
    }

    public function setID_INTERVIEW($value)
    {
        $this->ID_INTERVIEW = $value;
    }

    public function getTITRE_TEST_TECHNIQUE()
    {
        return $this->TITRE_TEST_TECHNIQUE;
    }

    public function setTITRE_TEST_TECHNIQUE($value)
    {
        $this->TITRE_TEST_TECHNIQUE = $value;
    }

    public function getDESCRIPTION_TEST_TECHNIQUE()
    {
        return $this->DESCRIPTION_TEST_TECHNIQUE;
    }

    public function setDESCRIPTION_TEST_TECHNIQUE($value)
    {
        $this->DESCRIPTION_TEST_TECHNIQUE = $value;
    }

    public function getDUREE_TEST_TECHNIQUE()
    {
        return $this->DUREE_TEST_TECHNIQUE;
    }

    public function setDUREE_TEST_TECHNIQUE($value)
    {
        $this->DUREE_TEST_TECHNIQUE = $value;
    }

    public function getSTATUT_TEST_TECHNIQUE()
    {
        return $this->STATUT_TEST_TECHNIQUE;
    }

    public function setSTATUT_TEST_TECHNIQUE($value)
    {
        $this->STATUT_TEST_TECHNIQUE = $value;
    }

    public function getDATE_CREATION_TEST_TECHNIQUE()
    {
        return $this->DATE_CREATION_TEST_TECHNIQUE;
    }

    public function setDATE_CREATION_TEST_TECHNIQUE($value)
    {
        $this->DATE_CREATION_TEST_TECHNIQUE = $value;
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
