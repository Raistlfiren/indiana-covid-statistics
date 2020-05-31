<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class YearlyDeath
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class YearlyDeath
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
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $deaths;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="yearlyDeath")
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
     * @return YearlyDeath
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     * @return YearlyDeath
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeaths()
    {
        return $this->deaths;
    }

    /**
     * @param mixed $deaths
     * @return YearlyDeath
     */
    public function setDeaths($deaths)
    {
        $this->deaths = $deaths;
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
     * @return YearlyDeath
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}