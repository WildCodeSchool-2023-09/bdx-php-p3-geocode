<?php

namespace App\Repository;

use App\Entity\Opened;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Opened>
 *
 * @method Opened|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opened|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opened[]    findAll()
 * @method Opened[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpenedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opened::class);
    }

//    /**
//     * @return Opened[] Returns an array of Opened objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Opened
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
