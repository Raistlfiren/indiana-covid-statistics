<?php


namespace App\Repository;


use App\Entity\Age;
use App\Entity\County;
use App\Entity\Day;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Age::class);
    }

    public function getAgeDetailsByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->select('a')
            ->innerJoin('a.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->getQuery()
            ->getResult();
    }
}