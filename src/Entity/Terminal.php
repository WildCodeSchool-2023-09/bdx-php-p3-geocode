<?php

namespace App\Entity;

use App\Repository\TerminalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerminalRepository::class)]
class Terminal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $latitudeY = null;

    #[ORM\Column]
    private ?float $longitudeX = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $outletType = null;

    #[ORM\Column]
    private ?int $numberOutlet = null;

    #[ORM\Column]
    private ?int $maxPower = null;

    #[ORM\ManyToOne(inversedBy: 'terminal')]
    private ?Town $town = null;

    #[ORM\ManyToMany(targetEntity: Opened::class, mappedBy: 'terminal')]
    private Collection $openeds;

    public function __construct()
    {
        $this->openeds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitudeY(): ?float
    {
        return $this->latitudeY;
    }

    public function setLatitudeY(float $latitudeY): static
    {
        $this->latitudeY = $latitudeY;

        return $this;
    }

    public function getLongitudeX(): ?float
    {
        return $this->longitudeX;
    }

    public function setLongitudeX(float $longitudeX): static
    {
        $this->longitudeX = $longitudeX;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getOutletType(): ?string
    {
        return $this->outletType;
    }

    public function setOutletType(string $outletType): static
    {
        $this->outletType = $outletType;

        return $this;
    }

    public function getNumberOutlet(): ?int
    {
        return $this->numberOutlet;
    }

    public function setNumberOutlet(int $numberOutlet): static
    {
        $this->numberOutlet = $numberOutlet;

        return $this;
    }

    public function getMaxPower(): ?int
    {
        return $this->maxPower;
    }

    public function setMaxPower(int $maxPower): static
    {
        $this->maxPower = $maxPower;

        return $this;
    }

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): static
    {
        $this->town = $town;

        return $this;
    }

    /**
     * @return Collection<int, Opened>
     */
    public function getOpeneds(): Collection
    {
        return $this->openeds;
    }

    public function addOpened(Opened $opened): static
    {
        if (!$this->openeds->contains($opened)) {
            $this->openeds->add($opened);
            $opened->addTerminal($this);
        }

        return $this;
    }

    public function removeOpened(Opened $opened): static
    {
        if ($this->openeds->removeElement($opened)) {
            $opened->removeTerminal($this);
        }

        return $this;
    }
}
