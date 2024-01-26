<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    #[Route('/route', name: 'app_route')]
    public function index(): Response
    {
        return $this->render('route/index.html.twig');
    }

    #[Route('/route2', name: 'app_route2')]
    public function index2(): Response
    {
        return $this->render('route/index2.html.twig');
    }

    #[Route('/route5', name: 'app_route5')]
    public function index5(): Response
    {
        return $this->render('route/index5.html.twig');
    }

    #[Route('/route4', name: 'app_route4')]
    public function index4(): Response
    {
        return $this->render('route/index4.html.twig');
    }
}
