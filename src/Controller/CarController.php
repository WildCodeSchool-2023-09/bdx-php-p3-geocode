<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security\UserAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class CarController extends AbstractController
{
    #[Route('/car/show/{id}', name: 'app_car', methods: ['GET', 'POST'])]
    public function profileCar(
        Car $car
    ): Response {

        return $this->render('car/userCar.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/car/register', name: 'app_car_register')]
    public function registerCar(
        Request $request,
        UserAuthenticatorInterface $userAuthenticator,
        EntityManagerInterface $entityManager,
    ): Response {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer la voiture à l'utilisateur actuellement connecté
            $car->setUser($this->getUser());

            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('car/register_car.html.twig', [
            'carForm' => $form->createView(),
        ]);
    }

    #[Route('/car/list', name: 'app_car_list')]
    public function listCars(): Response
    {
        $cars = $this->getUser()->getCars();

        return $this->render('car/list_cars.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/car/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function deleteCar(
        Request $request,
        Car $car,
        EntityManagerInterface $entityManager,
    ): Response {
//        dump($this->isCsrfTokenValid('delete' . $car->getId(),
// $request->request->get('_token')) ? "VALIDE" : "INVALIDE");
//        dump( $request->request->get('_token'));
//        dump('delete' . $car->getId());
        //TODO CSRF
//        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
//        }

        $this->addFlash('erreur', 'La voiture a été supprimé');
        return $this->render('car/delete.html.twig', [
            'car' => $car,
        ]);//        return $this->redirectToRoute('app_car_list', [
//        ], Response::HTTP_SEE_OTHER);
    }

    #[Route('/car/{id}/delete/confirm', name: 'app_car_confirm_delete')]
    public function confirmDeleteCar(Car $car): Response
    {
        return $this->render('car/delete.html.twig', [
            'car' => $car,
        ]);
    }
}
