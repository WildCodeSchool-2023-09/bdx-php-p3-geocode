<?php

namespace App\Controller;

use App\Entity\Town;
use App\Form\RouteType;
use App\Service\Route\RouteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    #[Route('/route', name: 'app_route')]
    public function ask(Request $request): Response
    {
        $form = $this->createForm(RouteType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $departure = $form->get('departure')->getData()->getId();
            $arrival = $form->get('arrival')->getData()->getId();
            $step = $form->get('step')->getData();
            return $this->redirectToRoute('app_route_show', [
                'departure' => $departure,
                'arrival' => $arrival,
                'step' => $step
            ]);
        }
        return $this->render('route/ask.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/routeshow/{departure}/{arrival}/{step}', name: 'app_route_show')]
    public function show(
        Town $departure,
        Town $arrival,
        int $step,
        Request $request
    ): Response {
        $session = $request->getSession();
        $session->set('departure', $departure->getId());
        $session->set('arrival', $arrival->getId());
        $session->set('step', $step);
        return $this->render('route/goTo.html.twig', [
            'departure' => $departure,
            'arrival' => $arrival,
            'step' => $step,
        ]);
    }

    #[Route('/api/route', name: 'app_api_route', methods: ['PUT'])]
    public function getRoute(Request $request, RouteService $routeService): Response
    {
        $step = $request->getPayload()->get('step');
        $jsonPoints = $request->getPayload()->get('points');
        $points = $routeService->getAllPoints($jsonPoints);
        $steps = $routeService->findAllSteps($points, $step);
        $terminals = $routeService->findTerminals($steps);
        return $this->json($terminals);
    }
}
