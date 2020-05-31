<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Sex
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Sex
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
    private $gender;

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
    private $covidTestProb;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidTest;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidTestPct;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCountPct;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeathsPct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="sexes")
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
     * @return Sex
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return Sex
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
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
     * @return Sex
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}