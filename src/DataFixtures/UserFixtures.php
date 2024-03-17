<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setGender('Ne pas spécifier');
        $user->setFirstname('Toto');
        $user->setLastname('Toto');
        $user->setBirthday(DateTime::createFromFormat('d/m/Y', '12/12/2002'));
        $user->setEmail('toto@toto.fr');
        $user->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'azertyui'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setGender($faker->randomElement(['Homme', 'Femme', 'Non binaire', 'Ne pas spécifier']));
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setBirthday($faker->dateTimeBetween('-80 years', '-18 years'));
            $user->setEmail($faker->email);
            $user->setRoles(['ROLE_CONTRIBUTOR']);
            $plainPassword = $faker->password(8, 40);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,$plainPassword
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
