<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Day;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

class DayRepository extends ServiceEntityRepository
{
    const TOTAL_NUMBER_OF_COUNTIES = 92;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Day::class);
    }

    public function getDaysByCounty($countyName)
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->select('d.id', 'd.date', 'd.covidCount', 'd.covidDeaths', 'd.covidTest')
            ->innerJoin('d.county', 'c')
            ->where('c.name = :county')
            ->setParameter('county', $countyName)
            ->orderBy('d.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getDataForLatestDay()
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->select('d.id', 'd.date', 'd.covidCount', 'd.covidDeaths', 'd.covidTest')
            ->orderBy('d.date', 'DESC')
            ->setMaxResults((self::TOTAL_NUMBER_OF_COUNTIES*2))
            ->getQuery()
            ->getResult();
    }

    public function getCountyMovingAverage(DateTime $currentDate, $countyName = null)
    {
        $next14 = clone $currentDate;
        $next14 = $next14->modify('-14 days');

        $qb = $this->createQueryBuilder('d');

        if (!empty($countyName)) {
            $qb->where('c.name = :county')
                ->setParameter('county', $countyName);
        }

        return $qb
            ->select('c.name', 'c.covidCount', 'c.covidDeaths', 'c.population', 'd.date', 'AVG(d1.covidCount) AS average', 'SUM(d1.covidCount) AS activeCases')
            ->innerJoin('d.county', 'c')
            ->join(Day::class, 'd1', 'WITH', 'd.county = d1.county AND DATE_DIFF(d.date, d1.date) BETWEEN 0 AND 13')
            ->andWhere('d.date BETWEEN :start AND :end')
            ->setParameter('start',  $next14->format('Y-m-d'))
            ->setParameter('end', $currentDate->format('Y-m-d'))
            ->groupBy('d.date')
            ->addGroupBy('d.county')
            ->orderBy('c.name')
            ->addOrderBy('d.date', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getCaseMovingAverage($days, County $county = null)
    {
        $latestDate = clone $county->getCreatedAt();

        $days--;

        $qb = $this->createQueryBuilder('d');

        if (! empty($county)) {
            $qb->where('c.name = :county')
                ->setParameter('county', $county->getName());
        }

        return $qb
            ->select('c.name')
            ->addSelect('d.date', 'd.covidCount')
            ->addSelect('AVG(d1.covidCount) AS dayAvg', "DATE_SUB(d.date, 1, 'DAY') AS prevDay")
            ->addSelect('AVG(d2.covidCount) AS prevDayAvg')
            ->join('d.county', 'c')
            ->join(Day::class, 'd1', 'WITH', 'd.county = d1.county AND DATE_DIFF(d.date, d1.date) BETWEEN 0 AND :days')
            ->join(Day::class, 'd2', 'WITH', "d.county = d2.county AND DATE_DIFF(DATE_SUB(d.date, 1, 'DAY'), d2.date) BETWEEN 0 AND :days")
            ->andWhere('DATE(d.date) >= :date')
            ->setParameter('days', $days)
            ->setParameter('date', $latestDate->modify('-1 day')->format('Y-m-d'))
            ->groupBy('d.date')
            ->addGroupBy('d.county')
            ->getQuery()
            ->getResult();
    }

    public function getDeathMovingAverage($days, County $county = null)
    {
        $latestDate = clone $county->getCreatedAt();

        $days--;

        $qb = $this->createQueryBuilder('d');

        if (! empty($county)) {
            $qb->where('c.name = :county')
                ->setParameter('county', $county->getName());
        }

        return $qb
            ->select('c.name')
            ->addSelect('d.date', 'd.covidDeaths')
            ->addSelect('AVG(d1.covidDeaths) AS dayAvg', "DATE_SUB(d.date, 1, 'DAY') AS prevDay")
            ->addSelect('AVG(d2.covidDeaths) AS prevDayAvg')
            ->join('d.county', 'c')
            ->join(Day::class, 'd1', 'WITH', 'd.county = d1.county AND DATE_DIFF(d.date, d1.date) BETWEEN 0 AND :days')
            ->join(Day::class, 'd2', 'WITH', "d.county = d2.county AND DATE_DIFF(DATE_SUB(d.date, 1, 'DAY'), d2.date) BETWEEN 0 AND :days")
            ->andWhere('DATE(d.date) >= :date')
            ->setParameter('days', $days)
            ->setParameter('date', $latestDate->modify('-1 day')->format('Y-m-d'))
            ->groupBy('d.date')
            ->addGroupBy('d.county')
            ->getQuery()
            ->getResult();
    }

    public function getWeeklyCaseSum($countyName = null)
    {
        $qb = $this->createQueryBuilder('d');

        if (!empty($countyName)) {
            $qb->where('c.name = :county')
                ->setParameter('county', $countyName);
        }

        return $qb->select('SUM(d.covidCount) AS total', 'WEEK(d.date) AS week_number', 'd.date')
            ->join('d.county', 'c')
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->getQuery()
            ->getResult();
    }

    public function getWeeklyDeathSum($countyName = null)
    {
        $qb = $this->createQueryBuilder('d');

        if (!empty($countyName)) {
            $qb->where('c.name = :county')
                ->setParameter('county', $countyName);
        }

        return $qb->select('SUM(d.covidDeaths) AS total', 'WEEK(d.date) AS week_number', 'd.date')
            ->join('d.county', 'c')
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->getQuery()
            ->getResult();
    }

    public function getChartCaseCount($county = null)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select('d.covidCount')
            ->addSelect('d.date');

        if (! empty($county)) {
            $qb->join('d.county', 'county');
            $qb->where('county.name = :county')->setParameter('county', $county);
        }

        $qb->orderBy('d.date', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }
}