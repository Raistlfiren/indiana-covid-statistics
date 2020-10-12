<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Age
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Age
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $age;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $covidCountProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $covidDeathsProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeaths;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $covidTestProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidTest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $covidTestPct;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $covidCountPct;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $covidDeathsPct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="ages")
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
     * @return Age
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     * @return Age
     */
    public function setAge($age)
    {
        $this->age = $age;
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
     * @return Age
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
     * @return Age
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
     * @return Age
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
     * @return Age
     */
    public function setCovidDeaths($covidDeaths)
    {
        $this->covidDeaths = $covidDeaths;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidTestProb()
    {
        return $this->covidTestProb;
    }

    /**
     * @param mixed $covidTestProb
     * @return Age
     */
    public function setCovidTestProb($covidTestProb)
    {
        $this->covidTestProb = $covidTestProb;
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
     * @return Age
     */
    public function setCovidTest($covidTest)
    {
        $this->covidTest = $covidTest;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidTestPct()
    {
        return $this->covidTestPct;
    }

    /**
     * @param mixed $covidTestPct
     * @return Age
     */
    public function setCovidTestPct($covidTestPct)
    {
        $this->covidTestPct = $covidTestPct;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidCountPct()
    {
        return $this->covidCountPct;
    }

    /**
     * @param mixed $covidCountPct
     * @return Age
     */
    public function setCovidCountPct($covidCountPct)
    {
        $this->covidCountPct = $covidCountPct;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCovidDeathsPct()
    {
        return $this->covidDeathsPct;
    }

    /**
     * @param mixed $covidDeathsPct
     * @return Age
     */
    public function setCovidDeathsPct($covidDeathsPct)
    {
        $this->covidDeathsPct = $covidDeathsPct;
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
     * @return Age
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}