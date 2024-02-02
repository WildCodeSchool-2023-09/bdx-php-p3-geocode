<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
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
use Symfony\Component\Security\Core\User\UserInterface;

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

        if (!$this->isGranted('ROLE_CONTRIBUTOR')) {
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

            // Vérifie si la création de la reservation est autorisée
            if ($bookingService->isBookingAllowed($startDateTime, $endDateTime, $terminal)) {
                $entityManager->persist($booking);
                $entityManager->flush();

                $session = $request->getSession();
                if (!empty($session->get('departure'))) {
                    $departure = $session->get('departure');
                    $arrival = $session->get('arrival');
                    $step = $session->get('step');
                    return $this->redirectToRoute('app_route_show', [
                        'departure' => $departure,
                        'arrival' => $arrival,
                        'step' => $step
                        ]);
                }

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

    /**
     * @throws \Exception
     */
    #[Route('/booking/show/{id}', name: 'app_booking_show', methods: ['GET'])]
    public function show(
        BookingService $bookingService
    ): Response {

        if (!$this->isGranted('ROLE_CONTRIBUTOR')) {
            $this->addFlash('danger', 'Vous devez être connecté en tant qu\'utilisateur.');
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $activeBookings = $bookingService->getActiveBookings($user);

        return $this->render('booking/show.html.twig', [
            'activeBookings' => $activeBookings,
        ]);
    }

    #[Route('/booking/history/{id}', name: 'app_booking_history', methods: ['GET'])]
    public function history(
        User $user
    ): Response {
        $bookings = $this->getUser()->getBookings();

        return $this->render('booking/history.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/booking/delete/{id}', name: 'app_booking_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $booking = $entityManager->getRepository(Booking::class)->find($id);

        if (!$booking) {
            throw $this->createNotFoundException('Réservation non trouvée');
        }

        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_user', [], Response::HTTP_SEE_OTHER);
    }
}
