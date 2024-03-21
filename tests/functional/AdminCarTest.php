<?php

namespace App\Tests\functional;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminCarTest extends WebTestCase
{
    public function testNonAdminAccessToCarIndexPage(): void
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Reference to the non-administrator user "Toto"
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/admin/car/');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testIfCreatedCarIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        //Fetching user id 1 = admin@geocode.com
        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('app_admin_car_new'));

        // car_admin is the CSS selector
        $form = $crawler->filter('form[name=car_admin]')->form([
            'car_admin[brand]' => "Tesla",
            'car_admin[model]' => "model Y",
            'car_admin[outletType]' => "Type 2",
            'car_admin[user]' => "2",
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/admin/car/');
        $client->followRedirect();

        //Verify if the car has been successfully created in the database
        $newCar = $entityManager->getRepository(Car::class)->findOneBy([
            'user' => 2,
            'brand' => 'Tesla',
            'model' => 'model Y']);
        $this->assertNotNull($newCar);
    }

    public function testIfShowCarIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $car = $entityManager->getRepository(Car::class)->findOneBy([
            'user' => 2,
            'brand' => 'Tesla',
            'model' => 'model Y'
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_admin_car_show', ['id' => $car->getId()])
        );

        $this->assertResponseIsSuccessful();

        // Verify if the details of the new car are displayed correctly
        $this->assertSelectorTextContains('h1', 'Véhicule');
        $this->assertSelectorTextContains('td.brand', 'Tesla');
        $this->assertSelectorTextContains('td.model', 'model Y');
        $this->assertSelectorTextContains('td.userId', 'Toto - Toto');
    }

    public function testIfUpdateCarIsSuccessfull(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        // Selects the car created by the test testIfCreatedCarIsSuccessfull
        $car = $entityManager->getRepository(Car::class)->findOneBy([
            'user' => 2,
            'brand' => 'Tesla',
            'model' => 'model Y'
        ]);

        $client->loginUser($user);
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_admin_car_edit', ['id' => $car->getID()])
        );

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=car_admin]')->form([
            'car_admin[brand]' => "Tesla",
            'car_admin[model]' => "model S",
            'car_admin[outletType]' => "Type 2",
            'car_admin[user]' => "2",
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/admin/car/');
        $client->followRedirect();

        $editCar = $entityManager->getRepository(Car::class)->findOneBy([
            'user' => 2,
            'brand' => 'Tesla',
            'model' => 'model S'
        ]);
        $this->assertNotNull($editCar);
    }

    public function testIfDeleteCarIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        $editedCar = $entityManager->getRepository(Car::class)->findOneBy([
            'user' => 2,
            'brand' => 'Tesla',
            'model' => 'model S'
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_admin_car_delete', ['id' => $editedCar->getID()])
        );

        $form = $crawler->filter('form[action="' . $urlGenerator->generate('app_admin_car_delete', [
                'id' => $editedCar->getID()
            ]) . '"]')
            ->form();

        $client->submit($form);

        $this->assertResponseRedirects('/admin/car/');
        $client->followRedirect();

        $deletedCar = $entityManager->getRepository(Car::class)->findOneBy([
            'user' => 2,
            'brand' => 'Tesla',
            'model' => 'model S'
        ]);
        $this->assertNull($deletedCar);
    }
}
