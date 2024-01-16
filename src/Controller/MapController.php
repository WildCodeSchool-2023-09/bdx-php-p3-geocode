<?php

namespace App\Controller;

use App\Repository\TerminalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    #[Route('/', name: 'app_map')]
    public function index(): Response
    {
        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
        ]);
    }

    #[Route('/getterminal/{longitude}/{latitude}', name:'app_map_get_terminals', methods: ['GET'])]
    public function sendTerminals(
        float $longitude,
        float $latitude,
        TerminalRepository $terminalRepository
    ): JsonResponse {
        $terminals = $terminalRepository->findNearPosition($longitude, $latitude);
        return $this->json($terminals);
    }
}
