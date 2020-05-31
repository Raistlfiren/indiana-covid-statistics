<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CountyPopulation
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class CountyPopulation
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
    private $population;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="countyPopulation")
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
     * @return CountyPopulation
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
     * @return CountyPopulation
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * @param mixed $population
     * @return CountyPopulation
     */
    public function setPopulation($population)
    {
        $this->population = $population;
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
     * @return CountyPopulation
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}