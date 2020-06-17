<?php


namespace App\Repository;


use App\Entity\County;
use App\Entity\Day;
use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Day::class);
    }

    public function getCountyMovingAverage(\DateTime $currentDate, $countyName = null)
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

        $now = new \DateTime();
        $next14 = (new \DateTime())->modify('-14 days');

        $conn = $this->getEntityManager()->getConnection();

        $countyClause = '';

        if (!empty($countyName)) {
            $countyClause = 'AND c.name = \'' . $countyName . '\'';
        }

        $sql = 'SELECT c.name, d1.date, d1.covid_count, ROUND(AVG(d2.covid_count)) AS 14DayAvg
            FROM day d1
            JOIN day d2 ON d1.county_id=d2.county_id AND DATEDIFF(d1.date, d2.date) BETWEEN 0 AND 13
            Inner JOIN county c on d1.county_id = c.id
            WHERE d1.date BETWEEN \'' . $next14->format('Y-m-d') . '\' AND \'' . $now->format('Y-m-d') . '\'
            ' . $countyClause . '
            GROUP BY d1.date, d1.county_id
            ORDER BY c.name, d1.date DESC
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getCaseMovingAverage($days, $countyName = null)
    {
        $days--;
        $conn = $this->getEntityManager()->getConnection();

        $countyClause = '';

        if (!empty($countyName)) {
            $countyClause = 'AND c.name = \'' . $countyName . '\'';
        }

        $sql = 'SELECT c.name, d1.date, d1.covid_count, ROUND(AVG(d2.covid_count)) AS dayAvg, SUBDATE(d1.date, 1) AS prevDate, ROUND(AVG(d3.covid_count)) AS prevDayAvg,
            IFNULL(ROUND(((ROUND(AVG(d2.covid_count)) - ROUND(AVG(d3.covid_count)))/ROUND(AVG(d3.covid_count)))*100), 0) AS perChange
            FROM day d1
            JOIN day d2 ON d1.county_id=d2.county_id AND DATEDIFF(d1.date, d2.date) BETWEEN 0 AND ' . $days . '
            JOIN day d3 ON d1.county_id=d3.county_id AND DATEDIFF(SUBDATE(d1.date, 1), d3.date) BETWEEN 0 AND ' . $days . '
            Inner JOIN county c on d1.county_id = c.id
            WHERE d1.date = (SELECT MAX(dMax.date) FROM day dMax) ' . $countyClause . '
            GROUP BY d1.date, d1.county_id
            ORDER BY IFNULL(ROUND(((ROUND(AVG(d2.covid_count)) - ROUND(AVG(d3.covid_count)))/ROUND(AVG(d3.covid_count)))*100), 0) DESC;
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getDeathMovingAverage($days, $countyName = ull)
    {
        $days--;
        $conn = $this->getEntityManager()->getConnection();

        $countyClause = '';

        if (!empty($countyName)) {
            $countyClause = 'AND c.name = \'' . $countyName . '\'';
        }

        $sql = 'SELECT c.name, d1.date, d1.covid_deaths, ROUND(AVG(d2.covid_deaths)) AS dayAvg, SUBDATE(d1.date, 1) AS prevDate, ROUND(AVG(d3.covid_deaths)) AS prevDayAvg,
            IFNULL(ROUND(((ROUND(AVG(d2.covid_deaths)) - ROUND(AVG(d3.covid_deaths)))/ROUND(AVG(d3.covid_deaths)))*100), 0) AS perChange
            FROM day d1
            JOIN day d2 ON d1.county_id=d2.county_id AND DATEDIFF(d1.date, d2.date) BETWEEN 0 AND ' . $days . '
            JOIN day d3 ON d1.county_id=d3.county_id AND DATEDIFF(SUBDATE(d1.date, 1), d3.date) BETWEEN 0 AND ' . $days . '
            Inner JOIN county c on d1.county_id = c.id
            WHERE d1.date = (SELECT MAX(dMax.date) FROM day dMax) ' . $countyClause . '
            GROUP BY d1.date, d1.county_id
            ORDER BY IFNULL(ROUND(((ROUND(AVG(d2.covid_deaths)) - ROUND(AVG(d3.covid_deaths)))/ROUND(AVG(d3.covid_deaths)))*100), 0) DESC
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getWeeklyCaseSum($countyName = null)
    {
        $conn = $this->getEntityManager()->getConnection();

        $countyClause = '';

        if (!empty($countyName)) {
            $countyClause = 'WHERE c.name = \'' . $countyName . '\'';
        }

        $sql = '
            SELECT SUM(day.covid_count) AS total, CONCAT(DATE_FORMAT(date, "%m/%d/%Y"), \' - \', DATE_FORMAT(date + INTERVAL 6 DAY, "%m/%d/%Y")) AS week, WEEK(date) AS week_number
                FROM day 
                Inner JOIN county c on day.county_id = c.id
                ' . $countyClause . '
                GROUP BY WEEK(date) 
                ORDER BY WEEK(date)
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getWeeklyDeathSum($countyName = null)
    {
        $conn = $this->getEntityManager()->getConnection();

        $countyClause = '';

        if (!empty($countyName)) {
            $countyClause = 'WHERE c.name = \'' . $countyName . '\'';
        }

        $sql = '
            SELECT SUM(day.covid_deaths) AS total, CONCAT(DATE_FORMAT(date, "%m/%d/%Y"), \' - \', DATE_FORMAT(date + INTERVAL 6 DAY, "%m/%d/%Y")) AS week, WEEK(date) AS week_number
                FROM day 
                Inner JOIN county c on day.county_id = c.id
                ' . $countyClause . '
                GROUP BY WEEK(date) 
                ORDER BY WEEK(date)
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}