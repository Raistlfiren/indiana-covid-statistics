<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Race
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Race
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"display"})
     */
    private $race;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidCountProb;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $covidCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $covidDeathsProb;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
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
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="races")
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
     * @return Race
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param mixed $race
     * @return Race
     */
    public function setRace($race)
    {
        $this->race = $race;
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
     * @return Race
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
     * @return Race
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
     * @return Race
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
     * @return Race
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
     * @return Race
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
     * @return Race
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
     * @return Race
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}