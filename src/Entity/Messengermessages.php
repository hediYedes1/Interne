<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Messengermessages
{

    #[ORM\Id]
    #[ORM\Column(type: "bigint")]
    private string $id;

    #[ORM\Column(type: "text")]
    private string $body;

    #[ORM\Column(type: "text")]
    private string $headers;

    #[ORM\Column(type: "string", length: 190)]
    private string $queuename;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdat;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $availableat;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $deliveredat;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($value)
    {
        $this->body = $value;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($value)
    {
        $this->headers = $value;
    }

    public function getQueuename()
    {
        return $this->queuename;
    }

    public function setQueuename($value)
    {
        $this->queuename = $value;
    }

    public function getCreatedat()
    {
        return $this->createdat;
    }

    public function setCreatedat($value)
    {
        $this->createdat = $value;
    }

    public function getAvailableat()
    {
        return $this->availableat;
    }

    public function setAvailableat($value)
    {
        $this->availableat = $value;
    }

    public function getDeliveredat()
    {
        return $this->deliveredat;
    }

    public function setDeliveredat($value)
    {
        $this->deliveredat = $value;
    }
}
