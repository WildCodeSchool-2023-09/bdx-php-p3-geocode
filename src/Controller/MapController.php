<?php

namespace App\Controller;

use App\Entity\Town;
use App\Entity\TownSearched;
use App\Form\SearchTownType;
use App\Form\TownNameAutocompleteField;
use App\Form\TownType;
use App\Repository\TerminalRepository;
use App\Repository\TownRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    #[Route('/', name: 'app_map')]
    public function index(Request $request): Response
    {
        $request->getSession()->clear();
        return $this->render('map/index.html.twig');
    }

    #[Route('/getterminal/{longitude}/{latitude}/{distance}', name:'app_map_get_terminals', methods: ['GET'])]
    public function sendTerminals(
        float $longitude,
        float $latitude,
        TerminalRepository $terminalRepository,
        int $distance = 10000,
    ): JsonResponse {
        $terminals = $terminalRepository->findNearPosition($longitude, $latitude, $distance);
        return $this->json($terminals);
    }

    #[Route('/search', name: 'app_map_search_town')]
    public function searchTown(Request $request, EntityManagerInterface $entityManager): Response
    {
        $townSearched = new TownSearched();
        $form = $this->createForm(SearchTownType::class, $townSearched);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_map_show_town', ['id' => $townSearched->getTown()->getId()]);
        }
        return $this->render('map/search_town.html.twig', [
            'form' => $form,
            'town' => $townSearched,
        ]);
    }

    #[Route('/show/{id}', name: 'app_map_show_town')]
    public function viewTown(Town $town): Response
    {
        return $this->render('map/show_town.html.twig', ['town' => $town]);
    }
}
