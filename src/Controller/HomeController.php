<?php


namespace App\Controller;


use App\Entity\Day;
use App\Repository\AgeRepository;
use App\Repository\CountyRepository;
use App\Repository\DayRepository;
use App\Repository\EthnicityRepository;
use App\Repository\HospitalRepository;
use App\Repository\RaceRepository;
use App\Repository\SexRepository;
use App\Repository\StatisticsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}", name="county")
     */
    public function countyAction(
        Request $request,
        CountyRepository $countyRepository,
        string $selectedCounty
    )
    {
        $county = $countyRepository->findOneBy(['name' => $selectedCounty]);

//        $data = [];

//        $data = $serializer->serialize($county, 'array', ['groups' => ['display']]);

//        if ($county) {
//            $data['name'] = $county->getName();
//            $data['updatedDate'] = $county->getCreatedAt()->format(DATE_ISO8601);
//        }

        return $this->json($county, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/counties", name="counties")
     */
    public function countiesAction(CountyRepository $countyRepository)
    {
        $counties = $countyRepository->findBy([], ['name' => 'ASC']);

        $data = [];

        foreach ($counties as $county) {
            $data['counties'][] = $county->getName();
        }

        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/days", name="days")
     */
    public function daysAction(
        DayRepository $dayRepository,
        string $selectedCounty
    )
    {
        $days = $dayRepository->getDaysByCounty($selectedCounty);

        return $this->json($days, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/statistics", name="statistics")
     */
    public function statsAction(
        StatisticsRepository $statisticsRepository,
        string $selectedCounty
    )
    {
        $statistics = $statisticsRepository->getStatisticsByCounty($selectedCounty);

        return $this->json($statistics, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/hospital", name="hospital")
     */
    public function hospitalAction(
        HospitalRepository $hospitalRepository,
        string $selectedCounty
    )
    {
        $statistics = $hospitalRepository->getHospitalStatisticsByCounty($selectedCounty);

        return $this->json($statistics, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/age", name="age")
     */
    public function ageAction(
        AgeRepository $ageRepository,
        string $selectedCounty
    )
    {
        $statistics = $ageRepository->getAgeDetailsByCounty($selectedCounty);

        return $this->json($statistics, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/race", name="race")
     */
    public function raceAction(
        RaceRepository $raceRepository,
        string $selectedCounty
    )
    {
        $statistics = $raceRepository->getRaceDetailsByCounty($selectedCounty);

        return $this->json($statistics, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/ethnicity", name="ethnicity")
     */
    public function ethnicityAction(
        EthnicityRepository $ethnicityRepository,
        string $selectedCounty
    )
    {
        $statistics = $ethnicityRepository->getEthnicityDetailsByCounty($selectedCounty);

        return $this->json($statistics, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @param CountyRepository $countyRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/county/{selectedCounty}/sex", name="sex")
     */
    public function sexAction(
        SexRepository $sexRepository,
        string $selectedCounty
    )
    {
        $statistics = $sexRepository->getSexDetailsByCounty($selectedCounty);

        return $this->json($statistics, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/indiana", name="indiana")
     */
    public function indianaAction(Request $request, DayRepository $dayRepository, SerializerInterface $serializer)
    {
        $json = file_get_contents(__DIR__ . '/../../Resources/tl_2010_18_county10.json');
        $data = json_decode($json);
        $countyDays = $dayRepository->getDataForLatestDay();
        $json = $serializer->serialize($countyDays, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], ['groups' => ['display']]));
        $data->extra = json_decode($json);
        $data = json_encode($data);

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/indiana/days", name="indiana_days")
     */
    public function indianaCountyData(DayRepository $dayRepository)
    {
        $countyDays = $dayRepository->getDataForLatestDay();

        return $this->json($countyDays, 200, [], [
            'groups' => ['display']
        ]);
    }

    /**
     * @Route("/{reactRouting}/{test}", defaults={"reactRouting": null})
     * @Template()
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/{reactRouting}", defaults={"reactRouting": null})
     * @Template("home/index.html.twig")
     */
    public function testAction()
    {

    }

    /**
     * @param Request $request
     * @param DayRepository $dayRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/chart/case", name="chart_case")
     */
    public function dailyCaseAction(Request $request, DayRepository $dayRepository)
    {
        $data = [];

        $county = $request->get('county', 'Marion');

        /** @var Day[] $cases */
        $cases = $dayRepository->getChartCaseCount($county);

        foreach ($cases as $case) {
            $data['cases'][] = $case['covidCount'];
            $data['dates'][] = $case['date']->format('Y-m-d');
        }

        return $this->json($data);
    }
}