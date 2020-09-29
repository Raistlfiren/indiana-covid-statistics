<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Day;
use App\Entity\Hospital;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HospitalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hospital::class);
    }

    public function getHospitalStatisticsByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('h');

        return $qb
            ->select('h')
            ->innerJoin('h.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->getQuery()
            ->getSingleResult();
    }
}