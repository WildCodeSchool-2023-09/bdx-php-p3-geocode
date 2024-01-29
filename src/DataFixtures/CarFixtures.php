<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        //Recuperer les users
        $users = $manager->getRepository(User::class)->findAll();

        // Première boucle pour créer les voitures
        for ($i = 0; $i < count($users); $i++) {
            $user = $users[$i];

            $numberOfCars = $faker->numberBetween(1, 3);

            // créer le nombre spécifié de voitures pour chaque utilisateur
            for ($j = 0; $j < $numberOfCars; $j++) {
                $car = new Car();
                $car->setBrand($faker->company);
                $car->setModel($faker->word);
                $car->setOutletType($faker->randomElement([
                    'Type EF', 'Type 2', 'Type Combo CCS', 'Type CHAdeMO', 'Autres'
                ]));
                $car->setUser($user);

                $manager->persist($car);
            }
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Déclarez ici les classes de fixtures dont CarFixtures dépend
        return [
            UserFixtures::class,
        ];
    }
}
