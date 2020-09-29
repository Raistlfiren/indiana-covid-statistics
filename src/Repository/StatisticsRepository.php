<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistics::class);
    }

    public function getStatisticsByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb
            ->select('s.id', 's.newCaseDay', 's.newTestDay', 's.newDeathDay')
            ->innerJoin('s.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->getQuery()
            ->getSingleResult();
    }
}