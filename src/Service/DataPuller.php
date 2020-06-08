<?php

namespace App\Service;

use App\Entity\Age;
use App\Entity\County;
use App\Entity\Day;
use App\Entity\Ethnicity;
use App\Entity\Hospital;
use App\Entity\Race;
use App\Entity\Sex;
use App\Entity\Statistics;
use App\Repository\CountyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DataPuller
{
    const URL = 'https://www.coronavirus.in.gov/map/covid-19-indiana-daily-report-current.topojson';

    const COUNTY_STATISTICS_URL = 'https://hub.mph.in.gov/datastore/dump/8b8e6cd7-ede2-4c41-a9bd-4266df783145?bom=True';
    const CASE_DATA_URL = 'https://hub.mph.in.gov/datastore/dump/46b310b9-2f29-4a51-90dc-3886d9cf4ac1?bom=True';
    const CASE_DEMOGRAPHICS_URL = 'https://hub.mph.in.gov/datastore/dump/2538d7f1-391b-4733-90b3-9e95cd5f3ea6?bom=True';
    const BED_VENTS_URL = 'https://hub.mph.in.gov/datastore/dump/882a7426-886f-48cc-bbe0-a8d14e3012e4?bom=True';
    const STATE_WIDE_URL = 'https://hub.mph.in.gov/datastore/dump/182b6742-edac-442d-8eeb-62f96b17773e?bom=True';
    const REGION_WIDE_URL = 'https://hub.mph.in.gov/datastore/dump/3466ada6-a174-416d-a1c4-aaa0bf3d68af?bom=True';
    const COUNTY_WIDE_URL = 'https://hub.mph.in.gov/datastore/dump/afaa225d-ac4e-4e80-9190-f6800c366b58?bom=True';

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CountyRepository
     */
    private $countyRepository;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        CountyRepository $countyRepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->countyRepository = $countyRepository;
    }

    public function pull()
    {
        return file_get_contents(self::URL);
    }

    public function backupData($data)
    {
        $filePath = __DIR__ . '/../../Resources/coronavirus.in.gov/' . $this->generateFileName();

        file_put_contents($filePath, $data);

        $this->backupDataHub();
    }

    public function backupDataHub()
    {
        $date = new \DateTime();

        $countyStatistics = file_get_contents(self::COUNTY_STATISTICS_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/CountyStatistics/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $countyStatistics);

        $caseData = file_get_contents(self::CASE_DATA_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/CaseData/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $caseData);

        $caseDemographics = file_get_contents(self::CASE_DEMOGRAPHICS_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/CaseDemographics/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $caseDemographics);

        $bedsAndVents = file_get_contents(self::BED_VENTS_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/BedAndVents/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $bedsAndVents);

        $stateWide = file_get_contents(self::STATE_WIDE_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/StatewideTestCaseAndDeathTrends/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $stateWide);

        $regionWide = file_get_contents(self::REGION_WIDE_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/RegionWideTestCaseAndDeathTrends/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $regionWide);

        $countyWide = file_get_contents(self::COUNTY_WIDE_URL);
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/CountyWideTestCaseAndDeathTrends/' . $date->format('m-d-y') . '.csv';
        file_put_contents($filePath, $countyWide);
    }

    protected function generateFileName()
    {
        $date = new \DateTime();
        return 'covid-' . $date->format('m-d-y') . '.topojson';
    }

    public function isValidData($data)
    {
        $data = json_decode($data);

        return $data && property_exists($data, 'objects');
    }

    public function refreshDatabase($data)
    {
        $this->deleteOldData();

        $data = json_decode($data);

        $counties = $data->objects->cb_2015_indiana_county_20m->geometries;

        foreach ($counties as $county) {
            $countyProperties = $county->properties;
            $countyEntity = $this->countyRepository->findOneBy(['name' => $countyProperties->NAME]);

            if (! $county) {
                $countyEntity = new County();
                $countyEntity->setName($countyProperties->NAME);
            }

            $countyEntity->setPopulation($countyProperties->POPULATION);
            $countyEntity->setCovidCount($countyProperties->COVID_COUNT);
            $countyEntity->setCovidCountProb($countyProperties->COVID_COUNT_PROB);
            $countyEntity->setDistrict($countyProperties->ISDH_DISTRICT_ID);
            $countyEntity->setCovidTest($countyProperties->COVID_TEST);
            $countyEntity->setCovidDeaths($countyProperties->COVID_DEATHS);
            if (isset($countyProperties->COVID_DEATHS_PROB)) {
                $countyEntity->setCovidDeathsProb($countyProperties->COVID_DEATHS_PROB);
            }

            foreach ($countyProperties->VIZ_D_SEX as $sex) {
                $sexEntity = new Sex();
                $sexEntity->setGender($sex->GENDER);
                $sexEntity->setCovidCountProb($sex->COVID_COUNT_PROB);
                $sexEntity->setCovidCount($sex->COVID_COUNT);
                $sexEntity->setCovidDeathsProb($sex->COVID_DEATHS_PROB);
                $sexEntity->setCovidDeaths($sex->COVID_DEATHS);
                $sexEntity->setCovidTest($sex->COVID_TEST);
                $sexEntity->setCovidTestProb($sex->COVID_TEST_PROB);
                $sexEntity->setCovidTestPct($sex->COVID_TEST_PCT);
                $sexEntity->setCovidCountPct($sex->COVID_COUNT_PCT);
                $sexEntity->setCovidDeathsPct($sex->COVID_DEATHS_PCT);
                $countyEntity->addSexe($sexEntity);
            }

            foreach ($countyProperties->VIZ_D_AGE as $age) {
                $ageEntity = new Age();
                $ageEntity->setAge($age->AGEGRP);
                $ageEntity->setCovidCountProb($age->COVID_COUNT_PROB);
                $ageEntity->setCovidCount($age->COVID_COUNT);
                $ageEntity->setCovidDeathsProb($age->COVID_DEATHS_PROB);
                $ageEntity->setCovidDeaths($age->COVID_DEATHS);
                $ageEntity->setCovidTest($age->COVID_TEST);
                $ageEntity->setCovidTestProb($age->COVID_TEST_PROB);
                $ageEntity->setCovidTestPct($age->COVID_TEST_PCT);
                $ageEntity->setCovidCountPct($age->COVID_COUNT_PCT);
                $ageEntity->setCovidDeathsPct($age->COVID_DEATHS_PCT);
                $countyEntity->addAge($ageEntity);
            }

            foreach ($countyProperties->VIZ_D_RACE as $race) {
                $raceEntity = new Race();
                $raceEntity->setRace($race->RACE);
                $raceEntity->setCovidCountProb($race->COVID_COUNT_PROB);
                $raceEntity->setCovidCount($race->COVID_COUNT);
                $raceEntity->setCovidDeathsProb($race->COVID_DEATHS_PROB);
                $raceEntity->setCovidDeaths($race->COVID_DEATHS);
                $raceEntity->setCovidCountPct($race->COVID_COUNT_PCT);
                $raceEntity->setCovidDeathsPct($race->COVID_DEATHS_PCT);
                $countyEntity->addRace($raceEntity);
            }

            foreach ($countyProperties->VIZ_D_ETHNICITY as $ethnicity) {
                $ethnicityEntity = new Ethnicity();
                $ethnicityEntity->setEthnicity($ethnicity->ETHNICITY);
                $ethnicityEntity->setCovidCountProb($ethnicity->COVID_COUNT_PROB);
                $ethnicityEntity->setCovidCount($ethnicity->COVID_COUNT);
                $ethnicityEntity->setCovidDeathsProb($ethnicity->COVID_DEATHS_PROB);
                $ethnicityEntity->setCovidDeaths($ethnicity->COVID_DEATHS);
                $ethnicityEntity->setCovidCountPct($ethnicity->COVID_COUNT_PCT);
                $ethnicityEntity->setCovidDeathsPct($ethnicity->COVID_DEATHS_PCT);
                $countyEntity->addEthnicity($ethnicityEntity);
            }

            foreach ($countyProperties->VIZ_DATE as $date) {
                $dayEntity = new Day();
                $datetime = new \DateTime($date->DATE);
                $dayEntity->setDate($datetime);
                $dayEntity->setCovidTest($date->COVID_TEST);
                $dayEntity->setDailyDeltaTests($date->DAILY_DELTA_TESTS);
                $dayEntity->setDailyBaseTests($date->DAILY_BASE_TESTS);
                $dayEntity->setDailyBaseCasesProb($date->DAILY_BASE_CASES_PROB);
                $dayEntity->setDailyBaseCases($date->DAILY_BASE_CASES);
                $dayEntity->setDailyBaseDeathsProb($date->DAILY_BASE_DEATHS_PROB);
                $dayEntity->setDailyBaseDeaths($date->DAILY_BASE_DEATHS);
                $dayEntity->setCovidCountProb($date->COVID_COUNT_PROB);
                $dayEntity->setCovidCount($date->COVID_COUNT);
                $dayEntity->setCovidDeathsProb($date->COVID_DEATHS_PROB);
                $dayEntity->setCovidDeaths($date->COVID_DEATHS);
                $dayEntity->setDailyDeltaCasesProb($date->DAILY_DELTA_CASES_PROB);
                $dayEntity->setDailyDeltaCases($date->DAILY_DELTA_CASES);
                $dayEntity->setDailyDeltaDeathsProb($date->DAILY_DELTA_DEATHS_PROB);
                $dayEntity->setDailyDeltaDeaths($date->DAILY_DELTA_DEATHS);
                $dayEntity->setCovidCountCumsum($date->COVID_COUNT_CUMSUM);
                $dayEntity->setCovidDeathsCumsum($date->COVID_DEATHS_CUMSUM);
                $dayEntity->setCovidCountCumsumProb($date->COVID_COUNT_CUMSUM_PROB);
                $dayEntity->setCovidDeathsCumsumProb($date->COVID_DEATHS_CUMSUM_PROB);
                $dayEntity->setCovidTestCumsum($date->COVID_TEST_CUMSUM);

                //Exclude some faulty data from the dataset that dates back to 1983...
                if ($datetime > new \DateTime('01-01-20')) {
                    $countyEntity->addDay($dayEntity);
                }
            }

            foreach ($countyProperties->VIZ_VENTBED as $vent) {
                $hospitalEntity = new Hospital();
                $hospitalEntity->setBedsAllOccupiedBedsCovid($vent->beds_all_occupied_beds_covid_19);
                $hospitalEntity->setBedsIcuTotal($vent->beds_icu_total);
                $hospitalEntity->setBedsIcuOccupiedBedsCovid($vent->beds_icu_occupied_beds_covid_19);
                $hospitalEntity->setBedOccupiedIcuNonCovid($vent->beds_icu_occupied_beds_covid_19);
                $hospitalEntity->setBedsAvailableIcuBedsTtotal($vent->beds_available_icu_beds_total);
                $hospitalEntity->setVentsTotal($vent->vents_total);
                $hospitalEntity->setVentsAllInUseCovid($vent->vents_all_in_use_covid_19);
                $hospitalEntity->setVentsNonCovidPtsOnVents($vent->vents_non_covid_pts_on_vents);
                $hospitalEntity->setVentsAllAvailableVentsNotInUse($vent->vents_all_available_vents_not_in_use);
                $countyEntity->addHospital($hospitalEntity);
            }

            $daily = $countyProperties->DAILY_STATISTICS;

            $statisticsEntity = new Statistics();
            $statisticsEntity->setNewCaseDay($daily->new_case_day);
            $statisticsEntity->setNewCase($daily->new_case);
            $statisticsEntity->setNewTestDay($daily->new_test_day);
            $statisticsEntity->setNewTest($daily->new_test);
            $statisticsEntity->setNewDeathDay($daily->new_death_day);
            $statisticsEntity->setNewDeath($daily->new_death);
            $statisticsEntity->setNewTestStartDate(new \DateTime($daily->new_test_start_date));
            $statisticsEntity->setNewTestEndDate(new \DateTime($daily->new_test_end_date));
            $statisticsEntity->setNewCaseStartDate(new \DateTime($daily->new_case_start_date));
            $statisticsEntity->setNewCaseEndDate(new \DateTime($daily->new_case_end_date));
            $statisticsEntity->setNewDeathStartDate(new \DateTime($daily->new_death_start_date));
            $statisticsEntity->setNewDeathEndDate(new \DateTime($daily->new_death_end_date));
            $countyEntity->addStatistic($statisticsEntity);

            $this->entityManager->persist($countyEntity);
        }

        $this->entityManager->flush();
    }

    protected function deleteOldData()
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('DELETE FROM age');
        $connection->query('ALTER TABLE age AUTO_INCREMENT = 1');
        $connection->query('DELETE FROM day');
        $connection->query('ALTER TABLE day AUTO_INCREMENT = 1');
        $connection->query('DELETE FROM ethnicity');
        $connection->query('ALTER TABLE ethnicity AUTO_INCREMENT = 1');
        $connection->query('DELETE FROM hospital');
        $connection->query('ALTER TABLE hospital AUTO_INCREMENT = 1');

        $connection->query('DELETE FROM race');
        $connection->query('ALTER TABLE race AUTO_INCREMENT = 1');

        $connection->query('DELETE FROM sex');
        $connection->query('ALTER TABLE sex AUTO_INCREMENT = 1');

        $connection->query('DELETE FROM statistics');
        $connection->query('ALTER TABLE statistics AUTO_INCREMENT = 1');

        // Beware of ALTER TABLE here--it's another DDL statement and will cause
        // an implicit commit.
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $connection->commit();
    }
}