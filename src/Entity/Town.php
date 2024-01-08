<?php

namespace App\Entity;

use App\Repository\TownRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TownRepository::class)]
class Town
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $postalCode = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\OneToMany(mappedBy: 'town', targetEntity: User::class)]
    private Collection $user;

    #[ORM\OneToMany(mappedBy: 'town', targetEntity: Terminal::class)]
    private Collection $terminal;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->terminal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setTown($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTown() === $this) {
                $user->setTown(null);
            }
        }

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
            $terminal->setTown($this);
        }

        return $this;
    }

    public function removeTerminal(Terminal $terminal): static
    {
        if ($this->terminal->removeElement($terminal)) {
            // set the owning side to null (unless already changed)
            if ($terminal->getTown() === $this) {
                $terminal->setTown(null);
            }
        }

        return $this;
    }
}
