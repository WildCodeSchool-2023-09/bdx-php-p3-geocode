<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CssController extends AbstractController
{
    #[Route('/css', name: 'app_css')]
    public function index(): Response
    {
        return $this->render('css/index.html.twig', [
            'controller_name' => 'CssController',
        ]);
    }
}
