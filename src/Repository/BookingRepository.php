<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }


//    public function findOverlappingBookings(\DateTimeInterface $start, \DateTimeInterface $end): array
    public function findOverlapsBookings(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.datetimeStart < :end')
            ->andWhere('b.dateTimeEnd > :start')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    public function activeBookings(User $user): array
    {
        $currentDateTime = new DateTime();

        return $this->createQueryBuilder('b')
            ->andWhere('b.dateTimeEnd > :currentDateTime')
            ->andWhere('b.user = :user')
            ->setParameter('currentDateTime', $currentDateTime)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
