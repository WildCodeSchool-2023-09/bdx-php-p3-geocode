<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationsController extends AbstractController
{
    #[Route('/informations', name: 'app_informations')]
    public function index(): Response
    {
        return $this->render('informations/index.html.twig', [
            'controller_name' => 'InformationsController',
        ]);
    }

    #[Route('/informations/rgpd', name: 'app_rgpd')]
    public function rgpd(): Response
    {
        return $this->render('informations/rgpd.html.twig', [
            'controller_name' => 'InformationsController',
        ]);
    }
}
