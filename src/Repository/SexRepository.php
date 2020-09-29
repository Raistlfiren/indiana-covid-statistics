<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Day;
use App\Entity\Sex;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sex::class);
    }

    public function getSexDetailsByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb
            ->select('s')
            ->innerJoin('s.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->getQuery()
            ->getResult();
    }
}