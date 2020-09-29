<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Hospital
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Statistics
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $newCaseDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $newCase;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $newTestDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $newTest;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $newDeathDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $newDeath;

    /**
     * @ORM\Column(type="date")
     */
    private $newTestStartDate;

    /**
     * @ORM\Column(type="date")
     */
    private $newTestEndDate;

    /**
     * @ORM\Column(type="date")
     */
    private $newCaseStartDate;

    /**
     * @ORM\Column(type="date")
     */
    private $newCaseEndDate;

    /**
     * @ORM\Column(type="date")
     */
    private $newDeathStartDate;

    /**
     * @ORM\Column(type="date")
     */
    private $newDeathEndDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="statistics")
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
     * @return Statistics
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewCaseDay()
    {
        return $this->newCaseDay;
    }

    /**
     * @param mixed $newCaseDay
     * @return Statistics
     */
    public function setNewCaseDay($newCaseDay)
    {
        $this->newCaseDay = $newCaseDay;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewCase()
    {
        return $this->newCase;
    }

    /**
     * @param mixed $newCase
     * @return Statistics
     */
    public function setNewCase($newCase)
    {
        $this->newCase = $newCase;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewTestDay()
    {
        return $this->newTestDay;
    }

    /**
     * @param mixed $newTestDay
     * @return Statistics
     */
    public function setNewTestDay($newTestDay)
    {
        $this->newTestDay = $newTestDay;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewTest()
    {
        return $this->newTest;
    }

    /**
     * @param mixed $newTest
     * @return Statistics
     */
    public function setNewTest($newTest)
    {
        $this->newTest = $newTest;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewDeathDay()
    {
        return $this->newDeathDay;
    }

    /**
     * @param mixed $newDeathDay
     * @return Statistics
     */
    public function setNewDeathDay($newDeathDay)
    {
        $this->newDeathDay = $newDeathDay;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewDeath()
    {
        return $this->newDeath;
    }

    /**
     * @param mixed $newDeath
     * @return Statistics
     */
    public function setNewDeath($newDeath)
    {
        $this->newDeath = $newDeath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewTestStartDate()
    {
        return $this->newTestStartDate;
    }

    /**
     * @param mixed $newTestStartDate
     * @return Statistics
     */
    public function setNewTestStartDate($newTestStartDate)
    {
        $this->newTestStartDate = $newTestStartDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewTestEndDate()
    {
        return $this->newTestEndDate;
    }

    /**
     * @param mixed $newTestEndDate
     * @return Statistics
     */
    public function setNewTestEndDate($newTestEndDate)
    {
        $this->newTestEndDate = $newTestEndDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewCaseStartDate()
    {
        return $this->newCaseStartDate;
    }

    /**
     * @param mixed $newCaseStartDate
     * @return Statistics
     */
    public function setNewCaseStartDate($newCaseStartDate)
    {
        $this->newCaseStartDate = $newCaseStartDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewCaseEndDate()
    {
        return $this->newCaseEndDate;
    }

    /**
     * @param mixed $newCaseEndDate
     * @return Statistics
     */
    public function setNewCaseEndDate($newCaseEndDate)
    {
        $this->newCaseEndDate = $newCaseEndDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewDeathStartDate()
    {
        return $this->newDeathStartDate;
    }

    /**
     * @param mixed $newDeathStartDate
     * @return Statistics
     */
    public function setNewDeathStartDate($newDeathStartDate)
    {
        $this->newDeathStartDate = $newDeathStartDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewDeathEndDate()
    {
        return $this->newDeathEndDate;
    }

    /**
     * @param mixed $newDeathEndDate
     * @return Statistics
     */
    public function setNewDeathEndDate($newDeathEndDate)
    {
        $this->newDeathEndDate = $newDeathEndDate;
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
     * @return Statistics
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}