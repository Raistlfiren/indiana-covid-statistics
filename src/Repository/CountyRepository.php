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

    public function findWithDays()
    {
        $now = new \DateTime();
        $next14 = (new \DateTime())->modify('-14 days');
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('c', 'd')
            ->leftJoin('c.days', 'd')
            ->where('d.date BETWEEN :start AND :end')
            ->setParameter('start', $next14->format('Y-m-d'))
            ->setParameter('end', $now->format('Y-m-d'))
            ->orderBy('c.name')
            ->addOrderBy('d.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}