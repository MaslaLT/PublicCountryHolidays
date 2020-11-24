<?php

namespace App\Controller;

use App\Service\PchApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HolidaysController extends AbstractController
{

    /**
     * @Route (path="/", methods={"GET"}, name="home")
     */
    public function index(PchApi $pchApi)
    {
        $countryList = $pchApi->getSupportedCountries();

        return $this->render('publicHolidays/index.html.twig',
        [
            'countryList' => $countryList,
        ]);
    }

    /**
     * @Route (path="/search", methods={"GET"}, name="search")
     */
    public function search(Request $request, PchApi $pchApi)
    {
        $errors = [];
        $searchCountry = $request->query->get('country');
        $searchYear = $request->query->get('year');

        if (!is_string($searchCountry) || !is_string($searchYear)) {
            return $this->redirectToRoute('home');
        }

        $holidays = $pchApi->getHolidaysForYear($searchCountry, $searchYear);

        if (array_key_exists('error', $holidays)){
            $errors += $holidays;
        }

        $groupedHolidays = $this->groupHolidaysByMonth($holidays);

        $parameters = [
            'countryList' => $pchApi->getSupportedCountries(),
            'holidays' => $groupedHolidays,
            'searchCountry' => $searchCountry,
            'searchYear' => $searchYear,
            'errors' => $errors,
        ];


        return $this->render('publicHolidays/search.html.twig', $parameters);
    }

    protected function groupHolidaysByMonth(array $holidayArray): array
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
}