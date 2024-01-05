<?php

namespace App\Controller;

use App\Form\TownType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TownController extends AbstractController
{
    #[Route('/town', name: 'app_town')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TownType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $entityManager->persist($formData);
            $entityManager->flush();

//            return $this->redirectToRoute('app_login');
        }

        return $this->render('town/index.html.twig', [
            'townController' => 'TownController',
            'townForm' => $form->createView(),
//            'registrationForm' => $form->createView(),
        ]);
    }
}
