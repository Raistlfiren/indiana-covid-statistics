<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Day
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Day
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidTest;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyDeltaTests;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyBaseTests;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyBaseCasesProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyBaseCases;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyBaseDeathsProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyBaseDeaths;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCountProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeathsProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeaths;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyDeltaCasesProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyDeltaCases;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyDeltaDeathsProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyDeltaDeaths;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCountCumsum;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeathsCumsum;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCountCumsumProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeathsCumsumProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidTestCumsum;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="days")
     */
    private $county;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Day
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return Day
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidTest()
    {
        return $this->covidTest;
    }

    /**
     * @param mixed $covidTest
     * @return Day
     */
    public function setCovidTest($covidTest)
    {
        $this->covidTest = $covidTest;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyDeltaTests()
    {
        return $this->dailyDeltaTests;
    }

    /**
     * @param mixed $dailyDeltaTests
     * @return Day
     */
    public function setDailyDeltaTests($dailyDeltaTests)
    {
        $this->dailyDeltaTests = $dailyDeltaTests;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyBaseTests()
    {
        return $this->dailyBaseTests;
    }

    /**
     * @param mixed $dailyBaseTests
     * @return Day
     */
    public function setDailyBaseTests($dailyBaseTests)
    {
        $this->dailyBaseTests = $dailyBaseTests;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyBaseCasesProb()
    {
        return $this->dailyBaseCasesProb;
    }

    /**
     * @param mixed $dailyBaseCasesProb
     * @return Day
     */
    public function setDailyBaseCasesProb($dailyBaseCasesProb)
    {
        $this->dailyBaseCasesProb = $dailyBaseCasesProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyBaseCases()
    {
        return $this->dailyBaseCases;
    }

    /**
     * @param mixed $dailyBaseCases
     * @return Day
     */
    public function setDailyBaseCases($dailyBaseCases)
    {
        $this->dailyBaseCases = $dailyBaseCases;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyBaseDeathsProb()
    {
        return $this->dailyBaseDeathsProb;
    }

    /**
     * @param mixed $dailyBaseDeathsProb
     * @return Day
     */
    public function setDailyBaseDeathsProb($dailyBaseDeathsProb)
    {
        $this->dailyBaseDeathsProb = $dailyBaseDeathsProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyBaseDeaths()
    {
        return $this->dailyBaseDeaths;
    }

    /**
     * @param mixed $dailyBaseDeaths
     * @return Day
     */
    public function setDailyBaseDeaths($dailyBaseDeaths)
    {
        $this->dailyBaseDeaths = $dailyBaseDeaths;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidCountProb()
    {
        return $this->covidCountProb;
    }

    /**
     * @param mixed $covidCountProb
     * @return Day
     */
    public function setCovidCountProb($covidCountProb)
    {
        $this->covidCountProb = $covidCountProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidCount()
    {
        return $this->covidCount;
    }

    /**
     * @param mixed $covidCount
     * @return Day
     */
    public function setCovidCount($covidCount)
    {
        $this->covidCount = $covidCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidDeathsProb()
    {
        return $this->covidDeathsProb;
    }

    /**
     * @param mixed $covidDeathsProb
     * @return Day
     */
    public function setCovidDeathsProb($covidDeathsProb)
    {
        $this->covidDeathsProb = $covidDeathsProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidDeaths()
    {
        return $this->covidDeaths;
    }

    /**
     * @param mixed $covidDeaths
     * @return Day
     */
    public function setCovidDeaths($covidDeaths)
    {
        $this->covidDeaths = $covidDeaths;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyDeltaCasesProb()
    {
        return $this->dailyDeltaCasesProb;
    }

    /**
     * @param mixed $dailyDeltaCasesProb
     * @return Day
     */
    public function setDailyDeltaCasesProb($dailyDeltaCasesProb)
    {
        $this->dailyDeltaCasesProb = $dailyDeltaCasesProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyDeltaCases()
    {
        return $this->dailyDeltaCases;
    }

    /**
     * @param mixed $dailyDeltaCases
     * @return Day
     */
    public function setDailyDeltaCases($dailyDeltaCases)
    {
        $this->dailyDeltaCases = $dailyDeltaCases;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyDeltaDeathsProb()
    {
        return $this->dailyDeltaDeathsProb;
    }

    /**
     * @param mixed $dailyDeltaDeathsProb
     * @return Day
     */
    public function setDailyDeltaDeathsProb($dailyDeltaDeathsProb)
    {
        $this->dailyDeltaDeathsProb = $dailyDeltaDeathsProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyDeltaDeaths()
    {
        return $this->dailyDeltaDeaths;
    }

    /**
     * @param mixed $dailyDeltaDeaths
     * @return Day
     */
    public function setDailyDeltaDeaths($dailyDeltaDeaths)
    {
        $this->dailyDeltaDeaths = $dailyDeltaDeaths;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidDeathsCumsum()
    {
        return $this->covidDeathsCumsum;
    }

    /**
     * @param mixed $covidDeathsCumsum
     * @return Day
     */
    public function setCovidDeathsCumsum($covidDeathsCumsum)
    {
        $this->covidDeathsCumsum = $covidDeathsCumsum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidCountCumsumProb()
    {
        return $this->covidCountCumsumProb;
    }

    /**
     * @param mixed $covidCountCumsumProb
     * @return Day
     */
    public function setCovidCountCumsumProb($covidCountCumsumProb)
    {
        $this->covidCountCumsumProb = $covidCountCumsumProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidDeathsCumsumProb()
    {
        return $this->covidDeathsCumsumProb;
    }

    /**
     * @param mixed $covidDeathsCumsumProb
     * @return Day
     */
    public function setCovidDeathsCumsumProb($covidDeathsCumsumProb)
    {
        $this->covidDeathsCumsumProb = $covidDeathsCumsumProb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidTestCumsum()
    {
        return $this->covidTestCumsum;
    }

    /**
     * @param mixed $covidTestCumsum
     * @return Day
     */
    public function setCovidTestCumsum($covidTestCumsum)
    {
        $this->covidTestCumsum = $covidTestCumsum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     * @return Day
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidCountCumsum()
    {
        return $this->covidCountCumsum;
    }

    /**
     * @param mixed $covidCountCumsum
     * @return Day
     */
    public function setCovidCountCumsum($covidCountCumsum)
    {
        $this->covidCountCumsum = $covidCountCumsum;
        return $this;
    }
}