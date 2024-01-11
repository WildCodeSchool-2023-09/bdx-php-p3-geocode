<?php

namespace App\DataFixtures;

use App\Service\CsvTownService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TownFixtures extends Fixture
{
    public function __construct(private CsvTownService $csvService)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->csvService->readTown();
    }
}
