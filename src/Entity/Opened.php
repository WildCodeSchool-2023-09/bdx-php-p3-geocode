<?php

namespace App\Entity;

use App\Repository\OpenedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpenedRepository::class)]
class Opened
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    private ?string $day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeSlotStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timeSlotEnd = null;

    #[ORM\ManyToMany(targetEntity: Terminal::class, inversedBy: 'openeds')]
    private Collection $terminal;

    public function __construct()
    {
        $this->terminal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getTimeSlotStart(): ?\DateTimeInterface
    {
        return $this->timeSlotStart;
    }

    public function setTimeSlotStart(\DateTimeInterface $timeSlotStart): static
    {
        $this->timeSlotStart = $timeSlotStart;

        return $this;
    }

    public function getTimeSlotEnd(): ?\DateTimeInterface
    {
        return $this->timeSlotEnd;
    }

    public function setTimeSlotEnd(\DateTimeInterface $timeSlotEnd): static
    {
        $this->timeSlotEnd = $timeSlotEnd;

        return $this;
    }

    /**
     * @return Collection<int, Terminal>
     */
    public function getTerminal(): Collection
    {
        return $this->terminal;
    }

    public function addTerminal(Terminal $terminal): static
    {
        if (!$this->terminal->contains($terminal)) {
            $this->terminal->add($terminal);
        }

        return $this;
    }

    public function removeTerminal(Terminal $terminal): static
    {
        $this->terminal->removeElement($terminal);

        return $this;
    }
}
