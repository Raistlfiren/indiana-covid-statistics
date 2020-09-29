<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Day;
use App\Entity\Race;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Race::class);
    }

    public function getRaceDetailsByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('r');

        return $qb
            ->select('r')
            ->innerJoin('r.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->getQuery()
            ->getResult();
    }
}