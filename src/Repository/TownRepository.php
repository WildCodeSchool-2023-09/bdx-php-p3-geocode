<?php

namespace App\Repository;

use App\Entity\Town;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Town>
 *
 * @method Town|null find($id, $lockMode = null, $lockVersion = null)
 * @method Town|null findOneBy(array $criteria, array $orderBy = null)
 * @method Town[]    findAll()
 * @method Town[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Town::class);
    }

    public function getNbTowns(): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id) as total')
            ->getQuery()
            ->getSingleScalarResult();
    }


//    /**
//     * @return Town[] Returns an array of Town objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Town
    {
        return $this->createQueryBuilder('t')
            ->where('t.name = :n')
            ->setParameter('n', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByNameAndZipCode(string $name, string $zipCode): ?Town
    {
        return $this->createQueryBuilder('t')
            ->where('t.name = :n AND t.zipCode = :z')
            ->setParameter('n', $name)
            ->setParameter('z', $zipCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
//    public function findOneBySomeField($value): ?Town
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
