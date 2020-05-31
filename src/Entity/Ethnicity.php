<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ethnicity
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Ethnicity
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
    private $ethnicity;

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
    private $covidCountPct;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeathsPct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="ethnicities")
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
     * @return Ethnicity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEthnicity()
    {
        return $this->ethnicity;
    }

    /**
     * @param mixed $ethnicity
     * @return Ethnicity
     */
    public function setEthnicity($ethnicity)
    {
        $this->ethnicity = $ethnicity;
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
     * @return Ethnicity
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
     * @return Ethnicity
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
     * @return Ethnicity
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
     * @return Ethnicity
     */
    public function setCovidDeaths($covidDeaths)
    {
        $this->covidDeaths = $covidDeaths;
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
     * @return Ethnicity
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
     * @return Ethnicity
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
     * @return Ethnicity
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}