<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    public function __construct(
        int $issuerId, 
        \DateTime $date, 
        string $reason)
    {
        $this->setIssuerId($issuerId);
        $this->setDate($date);
        $this->setReason($reason);
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $issuerId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssuerId(): ?int
    {
        return $this->issuerId;
    }

    public function setIssuerId(int $issuerId): self
    {
        $this->issuerId = $issuerId;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}
