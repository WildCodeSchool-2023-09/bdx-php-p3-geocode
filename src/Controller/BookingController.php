<?php

namespace App\Controller;

use App\Entity\Booking;
use DateTime;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\TerminalRepository;
use App\Repository\UserRepository;
use App\Service\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
        ]);
    }

    #[Route('/booking/register/{id}', name: 'app_booking_register')]
    public function register(
        int $id,
        UserRepository $userRepository,
        TerminalRepository $terminalRepository,
        BookingRepository $bookingRepository,
        BookingService $bookingService,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('danger', 'Vous devez être connecté en tant qu\'utilisateur.');
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $terminal = $terminalRepository->find($id);

        $booking = new Booking();
        $booking->setUser($user);
        $booking->setTerminal($terminal);

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startDateTime = $booking->getDatetimeStart();
            $endDateTime = $booking->getDatetimeEnd();

            // Vérifie si la création du rendez-vous est autorisée
            if ($bookingService->isBookingAllowed($startDateTime, $endDateTime)) {
                $entityManager->persist($booking);
                $entityManager->flush();

                return $this->redirectToRoute('app_map');
            } else {
                $this->addFlash('danger', 'La date et l\'heure sélectionnées ne sont pas valides.');
            }
        }

        // Récupérez les créneaux horaires disponibles depuis le service
        $availableTimeSlots = $bookingService->getAvailableTimeSlots($terminal);

        return $this->render('booking/register.html.twig', [
            'form' => $form,
            'book' => $booking,
            'availableTimeSlots' => $availableTimeSlots,
        ]);
    }
}
