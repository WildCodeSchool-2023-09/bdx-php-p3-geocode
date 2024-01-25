<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $user->setGender('Autre');
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
    }
}
