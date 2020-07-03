<?php


namespace App\Repository;


use App\Entity\Age;
use App\Entity\County;
use App\Entity\Day;
use App\Entity\HistoryDay;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HistoryDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryDay::class);
    }

    public function aggregateData(County $county = null)
    {
        $qb = $this->createQueryBuilder('hd');

        if (! empty($county)) {
            $qb->where('hd.county = :county')
                ->andHaving('hd.county = :county')
                ->setParameter('county', $county);
        }

        return $qb
            ->select('hd.date', 'hd.dateAcquired', 'MAX(hd.covidCount) AS max_count', 'AVG(hd.covidCount) AS avg_count', 'MIN(hd.covidCount) AS min_count')
            ->groupBy('hd.date')
            ->addGroupBy('hd.county')
            ->orderBy('hd.date')
            ->andHaving("DATE(hd.date) >= '2020-05-19'")
            ->getQuery()
            ->getResult();
    }

    public function getLatestData(County $county = null)
    {
        $qb = $this->createQueryBuilder('hd');

        if (! empty($county)) {
            $qb->where('hd.county = :county')
                ->setParameter('county', $county);
        }

        return $qb
            ->select('hd.date', 'hd.covidCount')
            ->andWhere('DATE(hd.dateAcquired) = :date')
            ->andWhere("DATE(hd.date) >= '2020-05-19'")
            ->setParameter('date', $county->getCreatedAt()->format('Y-m-d'))
            ->orderBy('hd.date')
            ->getQuery()
            ->getResult();
    }
}