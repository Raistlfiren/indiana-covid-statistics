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

    public function getCaseMovingAverage($days, $countyName)
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

    public function getDeathMovingAverage($days, $countyName)
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
}