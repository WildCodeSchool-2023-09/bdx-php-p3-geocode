<?php

namespace App\Controller;

use App\Form\TownType;
use App\Repository\GeoTownRepository;
use App\Repository\TerminalRepository;
use App\Repository\TownRepository;
use App\Service\CsvTownService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TownController extends AbstractController
{
    #[Route('/town', name: 'app_town')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TownType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $entityManager->persist($formData);
            $entityManager->flush();

//            return $this->redirectToRoute('app_login');
        }

        return $this->render('town/index.html.twig', [
            'townController' => 'TownController',
            'townForm' => $form->createView(),
//            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/townfile', name: 'app_townfile')]
    public function townFile(CsvTownService $csvService): Response
    {
        return $this->render('town/test.html.twig', [
            'townfile' => $csvService->getFilename(),
            ]);
    }

    #[Route('/towns', name: 'app_towns')]
    public function getTownByName(
        TownRepository $townRepository,
        GeoTownRepository $geoTownRepository,
        TerminalRepository $terminalRepository,
    ): Response {
        $town = $townRepository->findOneByName('Gradignan');
        $point = $town->getPointAsString();
        $towns = $geoTownRepository->findNear($town, 10000);
        $gradignan = $townRepository->findOneByNameAndZipCode('gradignan', '33700');
        $terminals = $terminalRepository->findNearPosition(0, 45, 5000);
        return $this->render('town/test.html.twig', [
            'town' => $town,
            'towns' => $towns,
            'point' => $point,
            'gradignan' => $gradignan,
            'terminals' => $terminals,
        ]);
    }
}
