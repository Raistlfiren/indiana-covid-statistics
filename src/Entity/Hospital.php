<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Hospital
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Hospital
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bedsAllOccupiedBedsCovid;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedsIcuTotal;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedsIcuOccupiedBedsCovid;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedOccupiedIcuNonCovid;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedsAvailableIcuBedsTtotal;

    /**
     * @ORM\Column(type="integer")
     */
    private $ventsTotal;

    /**
     * @ORM\Column(type="integer")
     */
    private $ventsAllInUseCovid;

    /**
     * @ORM\Column(type="integer")
     */
    private $ventsNonCovidPtsOnVents;

    /**
     * @ORM\Column(type="integer")
     */
    private $ventsAllAvailableVentsNotInUse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\County", inversedBy="hospitals")
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
     * @return Hospital
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBedsAllOccupiedBedsCovid()
    {
        return $this->bedsAllOccupiedBedsCovid;
    }

    /**
     * @param mixed $bedsAllOccupiedBedsCovid
     * @return Hospital
     */
    public function setBedsAllOccupiedBedsCovid($bedsAllOccupiedBedsCovid)
    {
        $this->bedsAllOccupiedBedsCovid = $bedsAllOccupiedBedsCovid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBedsIcuTotal()
    {
        return $this->bedsIcuTotal;
    }

    /**
     * @param mixed $bedsIcuTotal
     * @return Hospital
     */
    public function setBedsIcuTotal($bedsIcuTotal)
    {
        $this->bedsIcuTotal = $bedsIcuTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBedsIcuOccupiedBedsCovid()
    {
        return $this->bedsIcuOccupiedBedsCovid;
    }

    /**
     * @param mixed $bedsIcuOccupiedBedsCovid
     * @return Hospital
     */
    public function setBedsIcuOccupiedBedsCovid($bedsIcuOccupiedBedsCovid)
    {
        $this->bedsIcuOccupiedBedsCovid = $bedsIcuOccupiedBedsCovid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBedOccupiedIcuNonCovid()
    {
        return $this->bedOccupiedIcuNonCovid;
    }

    /**
     * @param mixed $bedOccupiedIcuNonCovid
     * @return Hospital
     */
    public function setBedOccupiedIcuNonCovid($bedOccupiedIcuNonCovid)
    {
        $this->bedOccupiedIcuNonCovid = $bedOccupiedIcuNonCovid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBedsAvailableIcuBedsTtotal()
    {
        return $this->bedsAvailableIcuBedsTtotal;
    }

    /**
     * @param mixed $bedsAvailableIcuBedsTtotal
     * @return Hospital
     */
    public function setBedsAvailableIcuBedsTtotal($bedsAvailableIcuBedsTtotal)
    {
        $this->bedsAvailableIcuBedsTtotal = $bedsAvailableIcuBedsTtotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVentsTotal()
    {
        return $this->ventsTotal;
    }

    /**
     * @param mixed $ventsTotal
     * @return Hospital
     */
    public function setVentsTotal($ventsTotal)
    {
        $this->ventsTotal = $ventsTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVentsAllInUseCovid()
    {
        return $this->ventsAllInUseCovid;
    }

    /**
     * @param mixed $ventsAllInUseCovid
     * @return Hospital
     */
    public function setVentsAllInUseCovid($ventsAllInUseCovid)
    {
        $this->ventsAllInUseCovid = $ventsAllInUseCovid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVentsNonCovidPtsOnVents()
    {
        return $this->ventsNonCovidPtsOnVents;
    }

    /**
     * @param mixed $ventsNonCovidPtsOnVents
     * @return Hospital
     */
    public function setVentsNonCovidPtsOnVents($ventsNonCovidPtsOnVents)
    {
        $this->ventsNonCovidPtsOnVents = $ventsNonCovidPtsOnVents;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVentsAllAvailableVentsNotInUse()
    {
        return $this->ventsAllAvailableVentsNotInUse;
    }

    /**
     * @param mixed $ventsAllAvailableVentsNotInUse
     * @return Hospital
     */
    public function setVentsAllAvailableVentsNotInUse($ventsAllAvailableVentsNotInUse)
    {
        $this->ventsAllAvailableVentsNotInUse = $ventsAllAvailableVentsNotInUse;
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
     * @return Hospital
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }
}