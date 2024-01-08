<?php

namespace App\DataFixtures;

use App\Service\CsvService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TownFixtures extends Fixture
{
    public function __construct(private CsvService $csvService)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->csvService->readTown();
    }
}
