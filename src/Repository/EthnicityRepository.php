<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Day;
use App\Entity\Ethnicity;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EthnicityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ethnicity::class);
    }

    public function getEthnicityDetailsByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->select('e')
            ->innerJoin('e.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->getQuery()
            ->getResult();
    }
}