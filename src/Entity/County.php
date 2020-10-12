<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class County
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CountyRepository")
 */
class County
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $district;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $covidCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"display"})
     */
    private $covidCountProb;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $covidDeaths;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"display"})
     */
    private $covidDeathsProb;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $covidTest;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $population;

    /**
     * @ORM\Column(type="integer")
     */
    private $fips;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"display"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"display"})
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sex", mappedBy="county", cascade={"persist", "remove"})
     * @var Sex[]|ArrayCollection
     */
    private $sexes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Age", mappedBy="county", cascade={"persist", "remove"})
     * @var Age[]|ArrayCollection
     */
    private $ages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Race", mappedBy="county", cascade={"persist", "remove"})
     * @var Race|ArrayCollection
     */
    private $races;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ethnicity", mappedBy="county", cascade={"persist", "remove"})
     * @var Ethnicity|ArrayCollection
     */
    private $ethnicities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Day", mappedBy="county", cascade={"persist", "remove"})
     * @var Day|ArrayCollection
     */
    private $days;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hospital", mappedBy="county", cascade={"persist", "remove"})
     * @var Hospital[]|ArrayCollection
     */
    private $hospitals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Statistics", mappedBy="county", cascade={"persist", "remove"})
     * @var Statistics[]|ArrayCollection
     */
    private $statistics;

    public function __construct()
    {
        $this->sexes = new ArrayCollection();
        $this->ages = new ArrayCollection();
        $this->races = new ArrayCollection();
        $this->ethnicities = new ArrayCollection();
        $this->days = new ArrayCollection();
        $this->hospitals = new ArrayCollection();
        $this->statistics = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return County
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return County
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param mixed $district
     * @return County
     */
    public function setDistrict($district)
    {
        $this->district = $district;
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
     * @return County
     */
    public function setCovidCount($covidCount)
    {
        $this->covidCount = $covidCount;
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
     * @return County
     */
    public function setCovidCountProb($covidCountProb)
    {
        $this->covidCountProb = $covidCountProb;
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
     * @return County
     */
    public function setCovidDeaths($covidDeaths)
    {
        $this->covidDeaths = $covidDeaths;
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
     * @return County
     */
    public function setCovidDeathsProb($covidDeathsProb)
    {
        $this->covidDeathsProb = $covidDeathsProb;
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
     * @return County
     */
    public function setCovidTest($covidTest)
    {
        $this->covidTest = $covidTest;
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
     * @return County
     */
    public function setPopulation($population)
    {
        $this->population = $population;
        return $this;
    }


    /**
     * Sets createdAt.
     *
     * @param  \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets updatedAt.
     *
     * @param  \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param Sex $sexe
     */
    public function addSexe(Sex $sexe)
    {
        if ($this->sexes->contains($sexe)) {
            return;
        }

        $this->sexes->add($sexe);
        // uncomment if you want to update other side
        $sexe->setCounty($this);
    }

    /**
     * @param Sex $sexe
     */
    public function removeSexe(Sex $sexe)
    {
        $this->sexes->removeElement($sexe);
        // uncomment if you want to update other side
        $sexe->setCounty(null);
    }

    /**
     * @param Age $age
     */
    public function addAge(Age $age)
    {
        if ($this->ages->contains($age)) {
            return;
        }

        $this->ages->add($age);
        // uncomment if you want to update other side
        $age->setCounty($this);
    }

    /**
     * @param Age $age
     */
    public function removeAge(Age $age)
    {
        $this->ages->removeElement($age);
        // uncomment if you want to update other side
        $age->setCounty(null);
    }

    /**
     * @param mixed $race
     */
    public function addRace($race)
    {
        if ($this->races->contains($race)) {
            return;
        }

        $this->races->add($race);
        // uncomment if you want to update other side
        $race->setCounty($this);
    }

    /**
     * @param mixed $race
     */
    public function removeRace($race)
    {
        $this->races->removeElement($race);
        // uncomment if you want to update other side
        $race->setCounty(null);
    }

    /**
     * @param mixed $ethnicity
     */
    public function addEthnicity($ethnicity)
    {
        if ($this->ethnicities->contains($ethnicity)) {
            return;
        }

        $this->ethnicities->add($ethnicity);
        // uncomment if you want to update other side
        $ethnicity->setCounty($this);
    }

    /**
     * @param mixed $ethnicity
     */
    public function removeEthnicity($ethnicity)
    {
        $this->ethnicities->removeElement($ethnicity);
        // uncomment if you want to update other side
        $ethnicity->setCounty(null);
    }

    /**
     * @param mixed $day
     */
    public function addDay($day)
    {
        if ($this->days->contains($day)) {
            return;
        }

        $this->days->add($day);
        // uncomment if you want to update other side
        $day->setCounty($this);
    }

    /**
     * @param mixed $day
     */
    public function removeDay($day)
    {
        $this->days->removeElement($day);
        // uncomment if you want to update other side
        $day->setCounty(null);
    }

    /**
     * @param Hospital $hospital
     */
    public function addHospital(Hospital $hospital)
    {
        if ($this->hospitals->contains($hospital)) {
            return;
        }

        $this->hospitals->add($hospital);
        // uncomment if you want to update other side
        $hospital->setCounty($this);
    }

    /**
     * @param Hospital $hospital
     */
    public function removeHospital(Hospital $hospital)
    {
        $this->hospitals->removeElement($hospital);
        // uncomment if you want to update other side
        $hospital->setCounty(null);
    }

    /**
     * @param Statistics $statistic
     */
    public function addStatistic(Statistics $statistic)
    {
        if ($this->statistics->contains($statistic)) {
            return;
        }

        $this->statistics->add($statistic);
        // uncomment if you want to update other side
        $statistic->setCounty($this);
    }

    /**
     * @param Statistics $statistic
     */
    public function removeStatistic(Statistics $statistic)
    {
        $this->statistics->removeElement($statistic);
        // uncomment if you want to update other side
        $statistic->setCounty(null);
    }

    /**
     * @return Sex[]|ArrayCollection
     */
    public function getSexes()
    {
        return $this->sexes;
    }

    /**
     * @param Sex[]|ArrayCollection $sexes
     * @return County
     */
    public function setSexes($sexes)
    {
        $this->sexes = $sexes;
        return $this;
    }

    /**
     * @return Age[]|ArrayCollection
     */
    public function getAges()
    {
        return $this->ages;
    }

    /**
     * @param Age[]|ArrayCollection $ages
     * @return County
     */
    public function setAges($ages)
    {
        $this->ages = $ages;
        return $this;
    }

    /**
     * @return Race|ArrayCollection
     */
    public function getRaces()
    {
        return $this->races;
    }

    /**
     * @param Race|ArrayCollection $races
     * @return County
     */
    public function setRaces($races)
    {
        $this->races = $races;
        return $this;
    }

    /**
     * @return Ethnicity|ArrayCollection
     */
    public function getEthnicities()
    {
        return $this->ethnicities;
    }

    /**
     * @param Ethnicity|ArrayCollection $ethnicities
     * @return County
     */
    public function setEthnicities($ethnicities)
    {
        $this->ethnicities = $ethnicities;
        return $this;
    }

    /**
     * @return Day|ArrayCollection
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param Day|ArrayCollection $days
     * @return County
     */
    public function setDays($days)
    {
        $this->days = $days;
        return $this;
    }

    /**
     * @return Hospital[]|ArrayCollection
     */
    public function getHospitals()
    {
        return $this->hospitals;
    }

    /**
     * @param Hospital[]|ArrayCollection $hospitals
     * @return County
     */
    public function setHospitals($hospitals)
    {
        $this->hospitals = $hospitals;
        return $this;
    }

    /**
     * @return Statistics[]|ArrayCollection
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param Statistics[]|ArrayCollection $statistics
     * @return County
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFips()
    {
        return $this->fips;
    }

    /**
     * @param mixed $fips
     * @return County
     */
    public function setFips($fips)
    {
        $this->fips = $fips;
        return $this;
    }
}