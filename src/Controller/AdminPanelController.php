<?php

namespace App\Controller;

use App\Repository\TerminalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/admin/panel', name: 'app_admin_panel')]
    public function index(TerminalRepository $terminalRepository): Response
    {
        $nbTerminals = $terminalRepository->getNbTerminals();
        return $this->render('admin_panel/index.html.twig', [
            'nbTerminals' => $nbTerminals,
        ]);
    }
}
