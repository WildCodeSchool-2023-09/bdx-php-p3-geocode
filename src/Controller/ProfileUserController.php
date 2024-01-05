<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileUserController extends AbstractController
{
    #[Route('/profile/user', name: 'app_profile_user')]
    public function index(): Response
    {
        return $this->render('profile_user/index.html.twig', [
            'controller_name' => 'ProfileUserController',
        ]);
    }
}
