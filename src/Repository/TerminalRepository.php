<?php

namespace App\Repository;

use App\Entity\Terminal;
use App\Entity\Town;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Terminal>
 *
 * @method Terminal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terminal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terminal[]    findAll()
 * @method Terminal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerminalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terminal::class);
    }
    public function findNearTown(Town $town, int $distance = 10000): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT address, ST_X(point), ST_Y(point), number_outlet,
               ST_Distance_Sphere(point, ST_GeomFromText(:townlocation)) AS distance_m
        FROM terminal HAVING distance_m <= :distance';

        $resultSet = $conn->executeQuery($sql, [
            'townlocation' => $town->getPointAsString(),
            'distance' => $distance
        ]);
        return $resultSet->fetchAllAssociative();
    }

    public function findNearPosition(float $longitude, float $latitude, int $distance = 10000): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $target = 'POINT(' . $longitude . ' ' . $latitude . ')';
        $sql = '
        SELECT id, address, ST_X(point) as longitude, ST_Y(point) as latitude, number_outlet,
               ST_Distance_Sphere(point, ST_GeomFromText(:target)) AS distance_m
        FROM terminal HAVING distance_m <= :distance';

        $resultSet = $conn->executeQuery($sql, [
            'target' => $target,
            'distance' => $distance
        ]);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return Terminal[] Returns an array of Terminal objects
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

//    public function findOneBySomeField($value): ?Terminal
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getTerminals(int $page, int $pageSize): Paginator
    {
        //$pageSize = 200;
        $firstResult = ($page - 1) * $pageSize;

        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->setFirstResult($firstResult);
        $queryBuilder->setMaxResults($pageSize);

        $query = $queryBuilder->getQuery();

        return new Paginator($query);
    }

    public function getNbTerminals(): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id) as total')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
