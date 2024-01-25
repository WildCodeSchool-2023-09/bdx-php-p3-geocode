<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
//    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datetimeStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
//    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateTimeEnd = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Terminal $terminal = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetimeStart(): ?\DateTimeInterface
    {
        return $this->datetimeStart;
    }

    public function setDatetimeStart(\DateTimeInterface $datetimeStart): static
    {
        $this->datetimeStart = $datetimeStart;

        return $this;
    }

    public function getDateTimeEnd(): ?\DateTimeInterface
    {
        return $this->dateTimeEnd;
    }

    public function setDateTimeEnd(\DateTimeInterface $dateTimeEnd): static
    {
        $this->dateTimeEnd = $dateTimeEnd;

        return $this;
    }

    public function getTerminal(): ?Terminal
    {
        return $this->terminal;
    }

    public function setTerminal(?Terminal $terminal): static
    {
        $this->terminal = $terminal;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
