<?php


namespace App\Repository;


use App\Entity\County;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CountyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, County::class);
    }

    public function findWithDate($county)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('c', 'a', 'e', 'h', 'r', 's', 'st')
            ->where('c.name = :county')
            ->setParameter('county', $county)
            ->leftJoin('c.ages', 'a')
            ->leftJoin('c.days', 'd')
            ->leftJoin('c.ethnicities', 'e')
            ->leftJoin('c.hospitals', 'h')
            ->leftJoin('c.races', 'r')
            ->leftJoin('c.sexes' , 's')
            ->leftJoin('c.statistics', 'st')
            ->getQuery()
            ->getResult();
    }
}