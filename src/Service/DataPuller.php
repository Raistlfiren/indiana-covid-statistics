<?php

namespace App\Service;

use App\Entity\Age;
use App\Entity\County;
use App\Entity\Day;
use App\Entity\Ethnicity;
use App\Entity\HistoryDay;
use App\Entity\Hospital;
use App\Entity\Race;
use App\Entity\Sex;
use App\Entity\Statistics;
use App\Repository\CountyRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

class DataPuller
{
//    const URL = 'https://www.coronavirus.in.gov/map/covid-19-indiana-daily-report-current.topojson';
    const URL1 = 'https://www.coronavirus.in.gov/map/covid-19-indiana-ltc-report-current-public.json';
    const URL2 = 'https://www.coronavirus.in.gov/map/covid-19-indiana-universal-report-current-public.json';
    const URL3 = 'https://www.coronavirus.in.gov/map/ltc.json';
    const URL4 = 'https://www.coronavirus.in.gov/map/covid-19-indiana-school-report.json';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CountyRepository
     */
    private $countyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CountyRepository $countyRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->countyRepository = $countyRepository;
    }

    public function pull()
    {
        return file_get_contents(self::URL1);
    }

    public function pullUniversal()
    {
        return file_get_contents(self::URL2);
    }

    public function pullLTC()
    {
        return file_get_contents(self::URL3);
    }

    public function pullSchool()
    {
        return file_get_contents(self::URL4);
    }

    public function backupData($data)
    {
        $filePath = __DIR__ . '/../../Resources/coronavirus.in.gov/' . $this->generateFileName('public-');

        file_put_contents($filePath, $data);
    }

    public function backupUniversal($data)
    {
        $filePath = __DIR__ . '/../../Resources/coronavirus.in.gov/' . $this->generateFileName('universal-');

        file_put_contents($filePath, $data);
    }

    public function backupLTC($data)
    {
        $filePath = __DIR__ . '/../../Resources/coronavirus.in.gov/' . $this->generateFileName('ltc-');

        file_put_contents($filePath, $data);
    }

    public function backupSchool($data)
    {
        $filePath = __DIR__ . '/../../Resources/coronavirus.in.gov/' . $this->generateFileName('school-');

        file_put_contents($filePath, $data);
    }

    protected function generateFileName($additional = null)
    {
        $date = new \DateTime();
        return 'covid-' . $additional . $date->format('m-d-y') . '.topojson';
    }

    public function isValidData($data)
    {
        $data = json_decode($data);

        return $data && property_exists($data, 'objects') && count($data->objects->cb_2015_indiana_county_20m->geometries) === 92;
    }

    public function updateDays()
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('DELETE FROM day');
        $connection->query('ALTER TABLE day AUTO_INCREMENT = 1');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $connection->commit();

        $date = new \DateTime();
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/COVID-19-County-Wide-Test-Case-and-Death-Trends/';
        $csv = Reader::createFromPath($filePath . $date->format('m-d-y') . '.csv', 'r');

        $csv->setHeaderOffset(0); //set the CSV header offset

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $countyEntity = $this->countyRepository->findOneBy(['name' => $record['COUNTY_NAME']]);

//            if (! isset($record['COVID_TEST'])) {
//                echo 't';
//            }
            $dayEntity = new Day();
            $datetime = new \DateTime($record['DATE']);
            $dayEntity->setDate($datetime);
            $dayEntity->setCovidTest($record['COVID_TESTS']);
            $dayEntity->setCovidCount($record['COVID_COUNT']);
            $dayEntity->setCovidDeaths($record['COVID_COUNT']);
            $dayEntity->setCounty($countyEntity);

            $this->entityManager->persist($dayEntity);
        }
        $this->entityManager->flush();
    }

    public function updateHospitalData()
    {
        $this->deleteOldData();
        $date = new \DateTime();
        $filePath = __DIR__ . '/../../Resources/coronavirus.in.gov/covid-universal-';
        $data = json_decode(file_get_contents($filePath . $date->format('m-d-y') . '.topojson'));
        $dates = $data->metrics->data->date;
        $districts = $data->metrics->data->district;
        $icuCovids = $data->metrics->data->m2b_hospitalized_icu_occupied_covid;
        $icuNonCovids = $data->metrics->data->m2b_hospitalized_icu_occupied_non_covid;
        $icuAvailables = $data->metrics->data->m2b_hospitalized_icu_available;
        $icuSupplys = $data->metrics->data->m2b_hospitalized_icu_supply;
        $ventCovids = $data->metrics->data->m2b_hospitalized_vent_occupied_covid;
        $ventNonCovids = $data->metrics->data->m2b_hospitalized_vent_occupied_non_covid;
        $ventAvailables = $data->metrics->data->m2b_hospitalized_vent_available;
        $ventSupplys = $data->metrics->data->m2b_hospitalized_vent_supply;
        $covidTests = $data->metrics->data->m1e_covid_tests;
        $covidCases = $data->metrics->data->m1e_covid_cases;
        $covidDeaths = $data->metrics->data->m1e_covid_deaths;

        $dailyHospitalizations = [];

        $counties = [];
        $dailyStatistics = $data->metrics->daily_statistics;

        foreach ($dailyStatistics->fips_code_ext as $index => $fips_code) {
            $countyName = $dailyStatistics->county[$index];

            if ($countyName) {
                $counties[$fips_code] = [
                    'name' => $countyName,
                    'fips' => $fips_code,
                    'district' => $dailyStatistics->isdh_region[$index],
                    'population' => $dailyStatistics->population[$index],
                    'tests' => $dailyStatistics->covid_tests[$index],
                    'cases' => $dailyStatistics->covid_cases[$index],
                    'deaths' => $dailyStatistics->covid_deaths[$index]
                ];
            }
        }

        foreach ($districts as $index => $district) {
            if (array_key_exists($district, $counties)) {
                $counties[$district]['days'][] = [
                    'date' => $dates[$index],
                    'icuCovid' => $icuCovids[$index],
                    'icuNonCovid' => $icuNonCovids[$index],
                    'icuAvailable' => $icuAvailables[$index],
                    'icuSupply' => $icuSupplys[$index],
                    'ventCovid' => $ventCovids[$index],
                    'ventNonCovid' => $ventNonCovids[$index],
                    'ventAvailable' => $ventAvailables[$index],
                    'ventSupply' => $ventSupplys[$index],
                    'tests' => $covidTests[$index],
                    'cases' => $covidCases[$index],
                    'deaths' => $covidDeaths[$index],
                ];
            }
//            $dailyHospitalizations[$district][] = [
//                'date' => $dates[$index],
//                'icuCovid' => $icuCovids[$index],
//                'icuNonCovid' => $icuNonCovids[$index],
//                'icuAvailable' => $icuAvailables[$index],
//                'icuSupply' => $icuSupplys[$index],
//                'ventCovid' => $ventCovids[$index],
//                'ventNonCovid' => $ventNonCovids[$index],
//                'ventAvailable' => $ventAvailables[$index],
//                'ventSupply' => $ventSupplys[$index],
//            ];
        }

        $districts = $data->metrics->demographics->district;
        $demographics = $data->metrics->demographics;

        $countyDemographics = [];

        foreach ($districts as $index => $district) {
            if (array_key_exists($district, $counties)) {
                $counties[$district]['demographics']['age']['cases'] = [
                    '0-19' => $demographics->{'m1d_agegrp_covid_cases_0-19'}[$index],
                    '20-29' => $demographics->{'m1d_agegrp_covid_cases_20-29'}[$index],
                    '30-39' => $demographics->{'m1d_agegrp_covid_cases_30-39'}[$index],
                    '40-49' => $demographics->{'m1d_agegrp_covid_cases_40-49'}[$index],
                    '50-59' => $demographics->{'m1d_agegrp_covid_cases_50-59'}[$index],
                    '60-69' => $demographics->{'m1d_agegrp_covid_cases_60-69'}[$index],
                    '70-79' => $demographics->{'m1d_agegrp_covid_cases_70-79'}[$index],
                    '80' => $demographics->{'m1d_agegrp_covid_cases_80+'}[$index],
                    'unknown' => $demographics->{'m1d_agegrp_covid_cases_unknown'}[$index],
                ];

                $counties[$district]['demographics']['age']['deaths'] = [
                    '0-19' => $demographics->{'m1d_agegrp_covid_deaths_0-19'}[$index],
                    '20-29' => $demographics->{'m1d_agegrp_covid_deaths_20-29'}[$index],
                    '30-39' => $demographics->{'m1d_agegrp_covid_deaths_30-39'}[$index],
                    '40-49' => $demographics->{'m1d_agegrp_covid_deaths_40-49'}[$index],
                    '50-59' => $demographics->{'m1d_agegrp_covid_deaths_50-59'}[$index],
                    '60-69' => $demographics->{'m1d_agegrp_covid_deaths_60-69'}[$index],
                    '70-79' => $demographics->{'m1d_agegrp_covid_deaths_70-79'}[$index],
                    '80' => $demographics->{'m1d_agegrp_covid_deaths_80+'}[$index],
                    'unknown' => $demographics->{'m1d_agegrp_covid_deaths_unknown'}[$index],
                ];

                $counties[$district]['demographics']['age']['tests'] = [
                    '0-19' => $demographics->{'m1d_agegrp_covid_tests_0-19'}[$index],
                    '20-29' => $demographics->{'m1d_agegrp_covid_tests_20-29'}[$index],
                    '30-39' => $demographics->{'m1d_agegrp_covid_tests_30-39'}[$index],
                    '40-49' => $demographics->{'m1d_agegrp_covid_tests_40-49'}[$index],
                    '50-59' => $demographics->{'m1d_agegrp_covid_tests_50-59'}[$index],
                    '60-69' => $demographics->{'m1d_agegrp_covid_tests_60-69'}[$index],
                    '70-79' => $demographics->{'m1d_agegrp_covid_tests_70-79'}[$index],
                    '80' => $demographics->{'m1d_agegrp_covid_tests_80+'}[$index],
                    'unknown' => $demographics->{'m1d_agegrp_covid_tests_unknown'}[$index],
                ];

                $counties[$district]['demographics']['gender']['cases'] = [
                    'f' => $demographics->{'m1d_gender_covid_cases_f'}[$index],
                    'm' => $demographics->{'m1d_gender_covid_cases_m'}[$index],
                    'unknown' => $demographics->{'m1d_gender_covid_cases_unknown'}[$index],
                ];

                $counties[$district]['demographics']['gender']['deaths'] = [
                    'f' => $demographics->{'m1d_gender_covid_deaths_f'}[$index],
                    'm' => $demographics->{'m1d_gender_covid_deaths_m'}[$index],
                    'unknown' => $demographics->{'m1d_gender_covid_deaths_unknown'}[$index],
                ];

                $counties[$district]['demographics']['gender']['tests'] = [
                    'f' => $demographics->{'m1d_gender_covid_tests_f'}[$index],
                    'm' => $demographics->{'m1d_gender_covid_tests_m'}[$index],
                    'unknown' => $demographics->{'m1d_gender_covid_tests_unknown'}[$index],
                ];
            }
//            $countyDemographics[$district] = [
//                'cases-0-19' => $demographics->{'m1d_agegrp_covid_cases_0-19'}[$index],
//                'cases-20-29' => $demographics->{'m1d_agegrp_covid_cases_20-29'}[$index],
//                'cases-30-39' => $demographics->{'m1d_agegrp_covid_cases_30-39'}[$index],
//                'cases-40-49' => $demographics->{'m1d_agegrp_covid_cases_40-49'}[$index],
//                'cases-50-59' => $demographics->{'m1d_agegrp_covid_cases_50-59'}[$index],
//                'cases-60-69' => $demographics->{'m1d_agegrp_covid_cases_60-69'}[$index],
//                'cases-70-79' => $demographics->{'m1d_agegrp_covid_cases_70-79'}[$index],
//                'cases-80' => $demographics->{'m1d_agegrp_covid_cases_80+'}[$index],
//                'age-cases-unknown' => $demographics->{'m1d_agegrp_covid_cases_unknown'}[$index],
//                'deaths-0-19' => $demographics->{'m1d_agegrp_covid_deaths_0-19'}[$index],
//                'deaths-20-29' => $demographics->{'m1d_agegrp_covid_deaths_20-29'}[$index],
//                'deaths-30-39' => $demographics->{'m1d_agegrp_covid_deaths_30-39'}[$index],
//                'deaths-40-49' => $demographics->{'m1d_agegrp_covid_deaths_40-49'}[$index],
//                'deaths-50-59' => $demographics->{'m1d_agegrp_covid_deaths_50-59'}[$index],
//                'deaths-60-69' => $demographics->{'m1d_agegrp_covid_deaths_60-69'}[$index],
//                'deaths-70-79' => $demographics->{'m1d_agegrp_covid_deaths_70-79'}[$index],
//                'deaths-80' => $demographics->{'m1d_agegrp_covid_deaths_80+'}[$index],
//                'age-deaths-unknown' => $demographics->{'m1d_agegrp_covid_deaths_unknown'}[$index],
//                'tests-0-19' => $demographics->{'m1d_agegrp_covid_tests_0-19'}[$index],
//                'tests-20-29' => $demographics->{'m1d_agegrp_covid_tests_20-29'}[$index],
//                'tests-30-39' => $demographics->{'m1d_agegrp_covid_tests_30-39'}[$index],
//                'tests-40-49' => $demographics->{'m1d_agegrp_covid_tests_40-49'}[$index],
//                'tests-50-59' => $demographics->{'m1d_agegrp_covid_tests_50-59'}[$index],
//                'tests-60-69' => $demographics->{'m1d_agegrp_covid_tests_60-69'}[$index],
//                'tests-70-79' => $demographics->{'m1d_agegrp_covid_tests_70-79'}[$index],
//                'tests-80' => $demographics->{'m1d_agegrp_covid_tests_80+'}[$index],
//                'age-tests-unknown' => $demographics->{'m1d_agegrp_covid_tests_unknown'}[$index],
//                'cases-f' => $demographics->{'m1d_gender_covid_cases_f'}[$index],
//                'cases-m' => $demographics->{'m1d_gender_covid_cases_m'}[$index],
//                'cases-unknown' => $demographics->{'m1d_gender_covid_cases_unknown'}[$index],
//                'deaths-f' => $demographics->{'m1d_gender_covid_deaths_f'}[$index],
//                'deaths-m' => $demographics->{'m1d_gender_covid_deaths_m'}[$index],
//                'deaths-unknown' => $demographics->{'m1d_gender_covid_deaths_unknown'}[$index],
//                'tests-f' => $demographics->{'m1d_gender_covid_tests_f'}[$index],
//                'tests-m' => $demographics->{'m1d_gender_covid_tests_m'}[$index],
//                'tests-unknown' => $demographics->{'m1d_gender_covid_tests_unknown'}[$index],
//            ];
        }

        foreach ($counties as $county) {
            $countyEntity = $this->countyRepository->findOneBy(['name' => $county['name']]);

            if ($countyEntity === null) {
                $countyEntity = new County();
                $countyEntity->setName($county['name']);
            }

            $countyEntity->setPopulation($county['population']);
            $countyEntity->setCovidTest($county['tests']);
            $countyEntity->setCovidCount($county['cases']);
            $countyEntity->setCovidDeaths($county['deaths']);
            $countyEntity->setDistrict($county['district']);
            $countyEntity->setFips($county['fips']);

            foreach ($county['days'] as $index => $day) {
                $dayEntity = new Day();
                $datetime = new \DateTime($day['date']);
                $dayEntity->setDate($datetime);
                $dayEntity->setCovidDeaths($day['deaths']);
                $dayEntity->setCovidTest($day['tests']);
                $dayEntity->setCovidCount($day['cases']);
                $countyEntity->addDay($dayEntity);

                $totalDays = count($county['days']);

                //Only store the last hospital stats...
                if ($index === (count($county['days'])-1)) {
                    $hospital = new Hospital();
                    $hospital->setBedOccupiedIcuNonCovid($day['icuNonCovid']);
                    $hospital->setBedsIcuOccupiedBedsCovid($day['icuCovid']);
                    $hospital->setBedsIcuTotal($day['icuSupply']);
                    $hospital->setBedsAvailableIcuBedsTtotal($day['icuAvailable']);

                    $hospital->setVentsTotal($day['ventSupply']);
                    $hospital->setVentsNonCovidPtsOnVents($day['ventNonCovid']);
                    $hospital->setVentsAllInUseCovid($day['ventCovid']);
                    $hospital->setVentsAllAvailableVentsNotInUse($day['ventAvailable']);
                    $countyEntity->addHospital($hospital);
                }
            }

            $ageDemographics = $county['demographics']['age'];

            foreach ($ageDemographics['tests'] as $ageGroup => $total) {
                $age = new Age();
                $age->setAge($ageGroup);
                $age->setCovidTest($total);
                $age->setCovidCount($ageDemographics['cases'][$ageGroup]);
                $age->setCovidDeaths($ageDemographics['deaths'][$ageGroup]);
                $countyEntity->addAge($age);
            }

            $genderDemographics = $county['demographics']['gender'];
            foreach ($genderDemographics['tests'] as $genderGroup => $total) {
                $sex = new Sex();
                $sex->setGender($genderGroup);
                $sex->setCovidTest($total);
                $sex->setCovidCount($genderDemographics['cases'][$genderGroup]);
                $sex->setCovidDeaths($genderDemographics['deaths'][$genderGroup]);
                $countyEntity->addSexe($sex);
            }

            $this->entityManager->persist($countyEntity);
        }

        $this->entityManager->flush();
    }

    public function updateEthnicities()
    {
        $date = new \DateTime();
        $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/COVID-19-Demographics-by-County-and-District/';
        $csv = Reader::createFromPath($filePath . $date->format('m-d-y') . '.csv', 'r');

        $csv->setHeaderOffset(0); //set the CSV header offset

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $countyEntity = $this->countyRepository->findOneBy(['name' => $record->county_name]);

            $dayEntity = new Day();
            $dayEntity->setCovidTest($record->covid_test);
            $dayEntity->setCovidCount($record->covid_count);
            $dayEntity->setCovidDeaths($record->covid_deaths);
            $dayEntity->setCounty($countyEntity);
        }
    }

    public function refreshDatabase($data)
    {
        $this->deleteOldData();

        $data = json_decode($data);

        $counties = $data->objects->cb_2015_indiana_county_20m->geometries;

        $currentDatetime = new \DateTime('now', new \DateTimeZone('CST6CDT'));
        foreach ($counties as $county) {
            $countyProperties = $county->properties;
            $countyEntity = $this->countyRepository->findOneBy(['name' => $countyProperties->NAME]);

            if (! $countyEntity) {
                $countyEntity = new County();
                $countyEntity->setName($countyProperties->NAME);
            }

            $countyEntity->setCreatedAt($currentDatetime);
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
                    $this->storeHistoricalData($datetime, $currentDatetime, $countyEntity, $date);
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
        $connection->query('DELETE FROM county');
        $connection->query('ALTER TABLE county AUTO_INCREMENT = 1');
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

    public function storeHistoricalData(\DateTime $datetime, \DateTime $currentDatetime, County $countyEntity, $date)
    {
        $dayEntity = new HistoryDay();
        $dayEntity->setDateAcquired($currentDatetime);
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
        $dayEntity->setCounty($countyEntity);

        $this->entityManager->persist($dayEntity);
    }

//    public function storeHistoricalData()
//    {
//
//        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../Resources/coronavirus.in.gov'));
//
//        /** @var \DirectoryIterator $it */
//        $it->rewind();
//        while($it->valid()) {
//
//            if (!$it->isDot()) {
//                $data = file_get_contents($it->key());
//                $filename = $it->getFilename();
//                $filename = str_replace('covid-', '', $filename);
//                $datePulled = str_replace('.topojson', '', $filename);
//                $currentDate = \DateTime::createFromFormat('m-d-y', $datePulled);
//
//                $data = json_decode($data);
//
//                $counties = $data->objects->cb_2015_indiana_county_20m->geometries;
//
//                foreach ($counties as $county) {
//                    $countyProperties = $county->properties;
//                    $countyEntity = $this->countyRepository->findOneBy(['name' => $countyProperties->NAME]);
//
//                    foreach ($countyProperties->VIZ_DATE as $date) {
//                        $dayEntity = new HistoryDay();
//                        $datetime = new \DateTime($date->DATE);
//                        $dayEntity->setDateAcquired($currentDate);
//                        $dayEntity->setDate($datetime);
//                        $dayEntity->setCovidTest($date->COVID_TEST);
//                        $dayEntity->setDailyDeltaTests($date->DAILY_DELTA_TESTS);
//                        $dayEntity->setDailyBaseTests($date->DAILY_BASE_TESTS);
//                        $dayEntity->setDailyBaseCasesProb($date->DAILY_BASE_CASES_PROB);
//                        $dayEntity->setDailyBaseCases($date->DAILY_BASE_CASES);
//                        $dayEntity->setDailyBaseDeathsProb($date->DAILY_BASE_DEATHS_PROB);
//                        $dayEntity->setDailyBaseDeaths($date->DAILY_BASE_DEATHS);
//                        $dayEntity->setCovidCountProb($date->COVID_COUNT_PROB);
//                        $dayEntity->setCovidCount($date->COVID_COUNT);
//                        $dayEntity->setCovidDeathsProb($date->COVID_DEATHS_PROB);
//                        $dayEntity->setCovidDeaths($date->COVID_DEATHS);
//                        $dayEntity->setDailyDeltaCasesProb($date->DAILY_DELTA_CASES_PROB);
//                        $dayEntity->setDailyDeltaCases($date->DAILY_DELTA_CASES);
//                        $dayEntity->setDailyDeltaDeathsProb($date->DAILY_DELTA_DEATHS_PROB);
//                        $dayEntity->setDailyDeltaDeaths($date->DAILY_DELTA_DEATHS);
//                        $dayEntity->setCovidCountCumsum($date->COVID_COUNT_CUMSUM);
//                        $dayEntity->setCovidDeathsCumsum($date->COVID_DEATHS_CUMSUM);
//                        $dayEntity->setCovidCountCumsumProb($date->COVID_COUNT_CUMSUM_PROB);
//                        $dayEntity->setCovidDeathsCumsumProb($date->COVID_DEATHS_CUMSUM_PROB);
//                        $dayEntity->setCovidTestCumsum($date->COVID_TEST_CUMSUM);
//                        $dayEntity->setCounty($countyEntity);
//
//                        if ($datetime > new \DateTime('01-01-20')) {
//                            $this->entityManager->persist($dayEntity);
//                        }
//                    }
//
//                }
//
//                $this->entityManager->flush();
//                $this->entityManager->clear();
//
//            }
//
//            $it->next();
//        }
//
//    }
}