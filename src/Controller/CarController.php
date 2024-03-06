<?php

namespace App\Controller;

use App\Entity\Car;
//use App\Form\Car1Type;
use App\Entity\User;
use App\Form\CarType;
use App\Form\PictureCarType;
use App\Form\PictureUserType;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/car')]
class CarController extends AbstractController
{
    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car->setUser($this->getUser());
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_car_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/list', name: 'app_car_list')]
    public function listCars(): Response
    {
        $cars = $this->getUser()->getCars();

        return $this->render('car/list_cars.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/{id}/picture', name: 'app_car_picture', methods: ['GET', 'POST'])]
    public function modifyPicture(
        Request $request,
        Car $car,
        EntityManagerInterface $entityManager
    ): Response {
        $pictureForm = $this->createForm(PictureCarType::class, $car);
        $pictureForm->handleRequest($request);

        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'Votre image à été modifié');
            return $this->redirectToRoute('app_car_list');
        }

        return $this->render('car/pictureCar.html.twig', [
            'car' => $car,
            'pictureForm' => $pictureForm,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        if (
            $this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))
        ) {
            $entityManager->remove($car);
            $entityManager->flush();

            $this->addFlash('success', 'Votre véhicule a été supprimé');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression de votre véhicule');
        }

        return $this->redirectToRoute('app_car_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete/confirm', name: 'app_car_confirm_delete')]
    public function confirmDeleteCar(Car $car): Response
    {
        return $this->render('car/delete.html.twig', [
            'car' => $car,
        ]);
    }
}
