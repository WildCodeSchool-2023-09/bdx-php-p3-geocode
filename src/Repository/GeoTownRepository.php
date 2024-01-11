<?php

namespace App\Repository;

use App\Entity\Town;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GeoTownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Town::class);
    }

    public function findNear(Town $town, int $distance = 10000): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT name, zip_code,
               ST_Distance_Sphere(point, ST_GeomFromText(:townlocation)) AS distance_m
        FROM town HAVING distance_m <= :distance';

        $resultSet = $conn->executeQuery($sql, [
            'townlocation' => $town->getPointAsString(),
            'distance' => $distance
        ]);
        return $resultSet->fetchAllAssociative();
    }
}
