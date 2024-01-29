<?php

namespace App\Controller;

use App\Entity\Town;
use App\Form\RouteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    #[Route('/route', name: 'app_route')]
    public function ask(Request $request): Response
    {
        $result = [];
        $form = $this->createForm(RouteType::class, $result);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $departure = $form->get('departure')->getData()->getId();
            $arrival = $form->get('arrival')->getData()->getId();
            $step = $form->get('step')->getData();
            return $this->redirectToRoute('app_route_show', ['departure' => $departure,
                                                           'arrival' => $arrival,
                                                            'step' => $step]);
        }
        return $this->render('route/ask.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/routeshow/{departure}/{arrival}/{step}', name: 'app_route_show')]
    public function show(
        Town $departure,
        Town $arrival,
        int $step
    ): Response {
        return $this->render('route/goTo.html.twig', [
            'departure' => $departure,
            'arrival' => $arrival,
            'step' => $step,
        ]);
    }
}
