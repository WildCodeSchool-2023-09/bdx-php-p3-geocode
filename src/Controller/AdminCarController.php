<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarAdminType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/car')]
class AdminCarController extends AbstractController
{
    #[Route('/', name: 'app_admin_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('admin_car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        $form = $this->createForm(CarAdminType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('admin_car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarAdminType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete-picture', name: 'app_admin_car_delete_picture', methods: ['POST'])]
    public function deletePicture(
        Car $car,
        Request $request,
        EntityManagerInterface $entityManager,
        UploaderHelper $uploaderHelper
    ): Response {
        if ($this->isCsrfTokenValid('delete_picture' . $car->getId(), $request->request->get('_token'))) {
            // Supprimer le fichier physique en utilisant le service UploaderHelper
            $picturePath = $uploaderHelper->asset($car, 'pictureFile');
            $fileSystem = new Filesystem();
            $fileSystem->remove($picturePath);

            // Supprimer le nom du fichier de la base de données
            $car->setPicture(null);

            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'La photo de profil a été supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression de la photo de profil.');
        }

        return $this->redirectToRoute('app_admin_user_index', ['id' => $car->getId()], Response::HTTP_SEE_OTHER);
    }
}
