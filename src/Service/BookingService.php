<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\BookingRepository;
use DateInterval;
use DateTime;
use App\Entity\Terminal;
use DateTimeZone;
use Exception;

class BookingService
{
    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

//    public function isBookingAllowed(\DateTime $start, \DateTime $end): bool
    public function isBookingAllowed(\DateTimeInterface $start, \DateTimeInterface $end, ?Terminal $terminal): bool
    {
        // Vérifie si un rendez-vous existe déjà à la date et l'heure spécifiées
        $existingBooking = $this->bookingRepository->findOneBy([
            'datetimeStart' => $start,
            'dateTimeEnd' => $end,
            'terminal' => $terminal,
        ]);

        // Vérifie si la date de début est postérieure à maintenant
        $isStartValid = $start > new DateTime();

        // Vérifie si la date de fin est postérieure à la date de début
        $isEndValid = $end > $start;

        // Vérifie si la nouvelle réservation ne chevauche pas d'autres réservations existantes sur la même borne
        $overlappingBookings = $this->bookingRepository->findOverlapsBookings($start, $end, $terminal);
        $isNotOverlapping = empty($overlappingBookings);

        return $isStartValid && $isEndValid && $isNotOverlapping && !$existingBooking;
    }

    public function getAvailableTimeSlots(Terminal $terminal): array
    {
        // Récupérer toutes les réservations pour la borne spécifiée
        $bookings = $this->bookingRepository->findBy(['terminal' => $terminal]);

        // Générer tous les créneaux horaires pour une journée (par exemple)
        $allTimeSlots = $this->generateAllTimeSlots();

        // Supprimer les créneaux horaires déjà réservés
//        $availableTimeSlots = $this->removeBookedTimeSlots($allTimeSlots, $bookings);

        //marquer les créneaux horaires déjà réservés
        $availableTimeSlots = $this->markBookedTimeSlots($allTimeSlots, $bookings);

//        // Enlever les créneaux horaires passés
//        $availableTimeSlots = $this->removePastTimeSlots($availableTimeSlots);

        return $availableTimeSlots;
    }

    private function generateAllTimeSlots(): array
    {
        // Logique pour générer tous les créneaux horaires pour une journée
        // Retourne un tableau de \DateTimeInterface représentant les créneaux.

        $currentTime = new DateTime('now', new DateTimeZone('Europe/Paris'));

        $startTime = new DateTime();
//        $startTime->setTime($currentTime->format('H'), 0, 0);
        $startTime->setTime((int)$currentTime->format('H'), 0, 0);

        // Si l'heure actuelle est après la demi-heure, avancer d'une demi-heure supplémentaire
        if ($currentTime->format('i') > 30) {
            $startTime->add(new DateInterval('PT1H')); // Avancer d'une heure pour commencer à la demi-heure suivante
        } else {
            $startTime->add(new DateInterval('PT30M')); // Commencer à la demi-heure actuelle
        }

        $endTime = new DateTime('23:30:00');
        $interval = new DateInterval('PT30M');

        $allTimeSlots = [];

        while ($startTime <= $endTime) {
            $allTimeSlots[] = clone $startTime;
            $startTime->add($interval);
        }

        return $allTimeSlots;
    }

//    private function removeBookedTimeSlots(array $allTimeSlots, array $bookings): array
//    {
//        // Supprimer les créneaux horaires déjà réservés
//        foreach ($bookings as $booking) {
//            $start = $booking->getDatetimeStart();
//            $end = $booking->getDateTimeEnd();
//
//            foreach ($allTimeSlots as $key => $timeSlot) {
//                if ($timeSlot >= $start && $timeSlot < $end) {
//                    unset($allTimeSlots[$key]);
//                }
//            }
//        }
//
//        return array_values($allTimeSlots); // Réindexer le tableau après la suppression
//    }

    private function markBookedTimeSlots(array $allTimeSlots, array $bookings): array
    {
        // Marquer les créneaux horaires déjà réservés
        foreach ($bookings as $booking) {
            $start = $booking->getDatetimeStart();
            $end = $booking->getDateTimeEnd();

            foreach ($allTimeSlots as &$timeSlot) {
                if ($timeSlot >= $start && $timeSlot < $end) {
                    $timeSlot->booked = true; // Marquer l'objet DateTime directement
                }
            }
        }

        return $allTimeSlots;
    }

//    private function removePastTimeSlots(array $timeSlots): array
//    {
//        // Enlever les créneaux horaires passés
//        $currentTime = new DateTime();
//        foreach ($timeSlots as $key => $timeSlot) {
//            if ($timeSlot < $currentTime) {
//                unset($timeSlots[$key]);
//            }
//        }
//
//        return array_values($timeSlots); // Réindexer le tableau après la suppression
//    }

    /**
     * @throws \Exception
     */
    public function getActiveBookings(?User $user): array
    {
        if ($user === null) {
            throw new Exception();
        }
        return $this->bookingRepository->activeBookings($user);
    }
}
