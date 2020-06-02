<?php


namespace App\Controller;


use App\Entity\Age;
use App\Entity\Day;
use App\Entity\Ethnicity;
use App\Entity\Race;
use App\Entity\Sex;
use App\Repository\AgeRepository;
use App\Repository\CountyRepository;
use App\Repository\DayRepository;
use App\Repository\EthnicityRepository;
use App\Repository\HospitalRepository;
use App\Repository\RaceRepository;
use App\Repository\SexRepository;
use App\Repository\StatisticsRepository;
use MathPHP\Statistics\Average;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @param CountyRepository $countyRepository
     * @param StatisticsRepository $statisticsRepository
     * @param DayRepository $dayRepository
     * @param HospitalRepository $hospitalRepository
     * @param AgeRepository $ageRepository
     * @param EthnicityRepository $ethnicityRepository
     * @param SexRepository $sexRepository
     * @param RaceRepository $raceRepository
     * @param string $selectedCounty
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{selectedCounty}", name="homepage")
     */
    public function indexAction(
        CountyRepository $countyRepository,
        StatisticsRepository $statisticsRepository,
        DayRepository $dayRepository,
        HospitalRepository $hospitalRepository,
        AgeRepository $ageRepository,
        EthnicityRepository $ethnicityRepository,
        SexRepository $sexRepository,
        RaceRepository $raceRepository,
        string $selectedCounty = 'Marion'
    )
    {
        $county = $countyRepository->findOneBy(['name' => $selectedCounty]);

        if ($county === null) {
            //Someone must have been manipulating the DOM
            return $this->redirectToRoute('homepage');
        }

        $counties = $countyRepository->findBy([], ['name' => 'ASC']);
        $currentStatistics = $statisticsRepository->findOneBy(['county' => $county]);
        $hospitalStatistics = $hospitalRepository->findOneBy(['county' => $county]);
        $ageStatistics = $ageRepository->findBy(['county' => $county]);
        $ethnicityStatistics = $ethnicityRepository->findBy(['county' => $county]);
        $sexStatistics = $sexRepository->findBy(['county' => $county]);
        $raceStatistics = $raceRepository->findBy(['county' => $county]);
        $dailyStatistics = $dayRepository->findBy(['county' => $county], ['date' => 'DESC']);
        //$previousDay = $dayRepository->findOneBy(['county' => $county, 'date' => $date->modify('-1 day')]);
        $previousDay = $dailyStatistics[1];

        $cases = [];
        $tests = [];
        $deaths = [];
        $dailyCases = [];
        $dailyDeaths = [];
        $dailyTests = [];

        /**
         * @var integer $index
         * @var Day $dailyStatistic
         */
        foreach ($dailyStatistics as $index => $dailyStatistic) {
            $date = $dailyStatistic->getDate()->format('Y-m-d');

            if ($index < 7) {
                $cases['data'][] = $dailyStatistic->getCovidCount();
                $cases['dates'][] = $date;
                $tests['data'][] = $dailyStatistic->getCovidTest();
                $tests['dates'][] = $date;
                $deaths['data'][] = $dailyStatistic->getCovidDeaths();
                $deaths['dates'][] = $date;
            }

            $dailyCases['today'][] = $dailyStatistic->getCovidCount();
            $dailyCases['all'][] = $dailyStatistic->getCovidCountCumsum();
            $dailyCases['dates'][] = $date;

            $dailyDeaths['data'][] = $dailyStatistic->getCovidDeaths();
            $dailyDeaths['dates'][] = $date;

            $dailyTests['data'][] = $dailyStatistic->getCovidTest();
            $dailyTests['dates'][] = $date;
        }

        $dailyCases['ma7'] = array_pad(Average::simpleMovingAverage($dailyCases['today'], 7), count($dailyCases['today']), 0);
        $dailyCases['ma14'] = array_pad(Average::simpleMovingAverage($dailyCases['today'], 14), count($dailyCases['today']), 0);
        $dailyDeaths['ma7'] = array_pad(Average::simpleMovingAverage($dailyDeaths['data'], 7), count($dailyDeaths['dates']), 0);
        $dailyDeaths['ma14'] = array_pad(Average::simpleMovingAverage($dailyDeaths['data'], 14), count($dailyDeaths['dates']), 0);
        $dailyTests['ma7'] = array_pad(Average::simpleMovingAverage($dailyTests['data'], 7), count($dailyTests['dates']), 0);
        $dailyTests['ma14'] = array_pad(Average::simpleMovingAverage($dailyTests['data'], 14), count($dailyTests['dates']), 0);

        array_walk($dailyCases['ma7'], function(&$item){
            $item = round($item);
        });

        array_walk($dailyCases['ma14'], function(&$item){
            $item = round($item);
        });

        array_walk($dailyDeaths['ma7'], function(&$item){
            $item = round($item);
        });

        array_walk($dailyDeaths['ma14'], function(&$item){
            $item = round($item);
        });

        array_walk($dailyTests['ma7'], function(&$item){
            $item = round($item);
        });

        array_walk($dailyTests['ma14'], function(&$item){
            $item = round($item);
        });

        /**
         * @var Sex $sexStatistic
         */
        foreach ($sexStatistics as $sexStatistic) {
            $sexDetails['positives'][] = $sexStatistic->getCovidCount();
            $sexDetails['tests'][] = $sexStatistic->getCovidTest();
            $sexDetails['deaths'][] = $sexStatistic->getCovidDeaths();
            $sexDetails['labels'][] = $sexStatistic->getGender();
        }

        /**
         * @var Age $ageStatistic
         */
        foreach ($ageStatistics as $ageStatistic) {
            $ageDetails['positives'][] = $ageStatistic->getCovidCount();
            $ageDetails['tests'][] = $ageStatistic->getCovidTest();
            $ageDetails['deaths'][] = $ageStatistic->getCovidDeaths();
            $ageDetails['labels'][] = $ageStatistic->getAge();
        }

        /**
         * @var Ethnicity $ethnicityStatistic
         */
        foreach ($ethnicityStatistics as $ethnicityStatistic) {
            $ethnicityDetails['positives'][] = $ethnicityStatistic->getCovidCount();
            $ethnicityDetails['deaths'][] = $ethnicityStatistic->getCovidDeaths();
            $ethnicityDetails['labels'][] = $ethnicityStatistic->getEthnicity();
        }

        /**
         * @var Race $raceStatistic
         */
        foreach ($raceStatistics as $raceStatistic) {
            $raceDetails['positives'][] = $raceStatistic->getCovidCount();
            $raceDetails['deaths'][] = $raceStatistic->getCovidDeaths();
            $raceDetails['labels'][] = $raceStatistic->getRace();
        }

        $dailyBeds = [];
        $dailyVents = [];

        if ($hospitalStatistics) {
            $dailyBeds['data'] = [
                $hospitalStatistics->getBedsIcuOccupiedBedsCovid(),
                $hospitalStatistics->getBedOccupiedIcuNonCovid(),
                $hospitalStatistics->getBedsAvailableIcuBedsTtotal()
            ];
            $dailyBeds['all'] = $hospitalStatistics->getBedsIcuTotal();
            $dailyBeds['labels'] = ['Covid Use', 'Non-Covid Use', 'Available'];

            $dailyVents['data'] = [
                $hospitalStatistics->getVentsAllInUseCovid(),
                $hospitalStatistics->getVentsNonCovidPtsOnVents(),
                $hospitalStatistics->getVentsAllAvailableVentsNotInUse()
            ];
            $dailyVents['all'] = $hospitalStatistics->getVentsTotal();

            $dailyVents['labels'] = ['Covid Use', 'Non-Covid Use', 'Available'];
        }

        return $this->render('default/index.html.twig', [
            'county' => $county,
            'dailyStatistics' => $dailyStatistics,
            'currentStatistics' => $currentStatistics,
            'previousDay' => $previousDay,
            'counties' => $counties,
            'cases' => json_encode($cases),
            'tests' => json_encode($tests),
            'deaths' => json_encode($deaths),
            'dailyCases' => json_encode($dailyCases),
            'dailyDeaths' => json_encode($dailyDeaths),
            'dailyTests' => json_encode($dailyTests),
            'sexDetails' => json_encode($sexDetails),
            'ageDetails' => json_encode($ageDetails),
            'ethnicityDetails' => json_encode($ethnicityDetails),
            'raceDetails' => json_encode($raceDetails),
            'dailyBeds' => json_encode($dailyBeds),
            'dailyVents' => json_encode($dailyVents),
            'dailyBedsArray' => $dailyBeds,
            'dailyVentsArray' => $dailyVents
        ]);
    }
}