<?php

namespace App\Entity;

class TownSearched
{
    private Town $town;

    public function getTown(): Town
    {
        return $this->town;
    }

    public function setTown(Town $town): void
    {
        $this->town = $town;
    }
}
