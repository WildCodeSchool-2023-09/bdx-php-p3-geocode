<?php

namespace App\DataFixtures;

use App\Service\CsvService\CsvTerminalService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TerminalFixtures extends Fixture
{
    public function __construct(private CsvTerminalService $csvTerminalService)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $eta = -hrtime(true);
        $this->csvTerminalService->saveInDatabase();
        $eta += hrtime(true);
        echo 'done in ' . round($eta / 1e+9, 3) . ' seconds' . PHP_EOL;
    }
}
