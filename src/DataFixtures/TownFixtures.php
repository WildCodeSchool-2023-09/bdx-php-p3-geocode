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

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $eta = -hrtime(true);
        $this->csvService->saveInDatabase();
        $eta += hrtime(true);
        echo 'done in ' . round($eta / 1e+9, 3) . ' seconds' . PHP_EOL;
    }
}
