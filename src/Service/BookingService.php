<?php

namespace App\Service;

use App\Repository\BookingRepository;
use DateTime;

class BookingService
{
    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

//    public function isBookingAllowed(\DateTime $start, \DateTime $end): bool

//    public function isBookingAllowed(\DateTime $start, \DateTime $end): bool
    public function isBookingAllowed(\DateTimeInterface $start, \DateTimeInterface $end): bool
    {
        // Vérifie si un rendez-vous existe déjà à la date et l'heure spécifiées
        $existingBooking = $this->bookingRepository->findOneBy([
            'datetimeStart' => $start,
            'dateTimeEnd' => $end,
        ]);

        // Vérifie si la date de début est postérieure à maintenant
        $isStartValid = $start > new DateTime();

        // Vérifie si la date de fin est postérieure à la date de début
        $isEndValid = $end > $start;

        // Vérifie si la nouvelle réservation ne chevauche pas une réservation existante
        $isNotOverlapping = !$existingBooking;

        // Vérifie si la nouvelle réservation ne chevauche pas d'autres réservations existantes
        if ($isNotOverlapping) {
            $overlappingBookings = $this->bookingRepository->findOverlappingBookings($start, $end);
            $isNotOverlapping = empty($overlappingBookings);
        }

        return $isStartValid && $isEndValid && $isNotOverlapping;
    }
}
