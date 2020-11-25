<?php

namespace App\Controller;

use App\Service\PchApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HolidaysController extends AbstractController
{
    private $pchApi;

    public function __construct(PchApi $pchApi)
    {
        $this->pchApi = $pchApi;
    }

    /**
     * @Route (path="/", methods={"GET"}, name="home")
     */
    public function index()
    {
        $countryList = $this->pchApi->getSupportedCountries();

        return $this->render('publicHolidays/index.html.twig',
        [
            'countryList' => $countryList,
        ]);
    }

    /**
     * @Route (path="/search", methods={"GET"}, name="search")
     */
    public function search(Request $request)
    {
        $errors = [];
        $searchCountryCode = $request->query->get('country');
        $searchYear = $request->query->get('year');

        if (!is_string($searchCountryCode) || !is_string($searchYear)) {
            return $this->redirectToRoute('home');
        }

        $holidays = $this->pchApi->getHolidaysForYear($searchCountryCode, $searchYear);

        if (array_key_exists('error', $holidays)){
            $errors += $holidays;
        }

        $groupedHolidays = $this->groupHolidaysByMonth($holidays);

        $parameters = [
            'countryList' => $this->pchApi->getSupportedCountries(),
            'groupedHolidays' => $groupedHolidays,
            'searchCountry' => $searchCountryCode,
            'searchYear' => $searchYear,
            'errors' => $errors,
            'totalHolidaysAmount' => count($holidays),
            'todayIs' => $this->todayStatusIn($searchCountryCode),
            'maxHolidaysAndFreeDaysInRow' => $this->maxHolidayAndFreeDaysInRow($holidays),
        ];

        return $this->render('publicHolidays/search.html.twig', $parameters);
    }

    private function groupHolidaysByMonth(array $holidayArray): array
    {
        $firstArrayElement = array_shift($holidayArray);
        $firstMonth = $firstArrayElement['date']['month'];
        $arrayIndex = $firstMonth;
        $groupedArray[$arrayIndex][] = $firstArrayElement;
        $iterator = 0;

        foreach ($holidayArray as $holiday) {
            if($holiday['date']['month'] === $groupedArray[$arrayIndex][$iterator]['date']['month']){
                $groupedArray[$arrayIndex][] = $holiday;
                $iterator++;
            } else {
                $groupedArray[$holiday['date']['month']][] = $holiday;
                $iterator = 0;
            }
        }

        return $groupedArray;
    }

    private function todayStatusIn(string $searchCountryCode)
    {
        $todayIsWorkDay = $this->pchApi->isTodayWorkDay($searchCountryCode);
        $todayIsHoliday= $this->pchApi->isTodayHoliday($searchCountryCode);

        if ($todayIsWorkDay) {
            return 'workday';
        }

        if ($todayIsHoliday) {
            return 'holiday';
        }

        return 'free day';
    }

    private function maxHolidayAndFreeDaysInRow(array $holidays)
    {
//        $maxHolidayAndFreeDayInRow = 0;
//        $holidayCounter = 0;
//        $firstHoliday = array_pop($holidays);
//        $firstHolidayDate = $firstHoliday['date']['year'] . '-' .
//            $firstHoliday['date']['month'] . '-' .
//            $firstHoliday['date']['day'];
//
//        $startDate = new \DateTime($firstHolidayDate);
//
//        foreach ($holidays as $holiday) {
//            $holidayDate = new \DateTime(
//                $holiday['date']['year'] . '-' .
//                $holiday['date']['month'] . '-' .
//                $holiday['date']['day']
//            );
//
//            if($holidayCounter = 0)
//            {
//                if ($holiday['date']['dayOfWeek'] === 1) {
//                    $holidayCounter = $holidayCounter + 3;
//                }
//
//                if ($holiday['date']['dayOfWeek'] === 7) {
//                    $holidayCounter = $holidayCounter + 2;
//                }
//
//                if ($holiday['date']['dayOfWeek'] === 6) {
//                    $holidayCounter = $holidayCounter + 1;
//                }
//
//                continue;
//            }
//
//            $diff = $startDate->diff($holidayDate)->days;
//
//            if($diff == 1) {
//                $holidayCounter = $holidayCounter + 1;
//            }
//
//        }


//        dump($startDate);
//        die();
//        $date = new \DateTime('2021-01-31');
//        $date->add(new \DateInterval('P1D'));
//        $dateadded = $date->format('m-d');
//
//        dump($dateadded);
//        die();
////        foreach ($holidays as $holiday) {
////            if $holiday;
////        }
        return 'Not make it...';
    }

}