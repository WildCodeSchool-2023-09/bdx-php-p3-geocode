<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\TerminalRepository;
use App\Repository\TownRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/admin/panel', name: 'app_admin_panel')]
    public function index(
        TerminalRepository $terminalRepository,
        UserRepository $userRepository,
        CarRepository $carRepository,
        TownRepository $townRepository
    ): Response {
        $nbTowns = $townRepository->getNbTowns();
        $nbUsers = $userRepository->getNbUsers();
        $nbCars = $carRepository->getNbCars();
        $nbTerminals = $terminalRepository->getNbTerminals();
        return $this->render('admin_panel/index.html.twig', [
            'nbTerminals' => $nbTerminals,
            'nbTown' => $nbTowns,
            'nbUsers' => $nbUsers,
            'nbCars' => $nbCars
        ]);
    }
}
