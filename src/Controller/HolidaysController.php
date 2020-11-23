<?php

namespace App\Controller;

use App\Service\PchApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HolidaysController extends AbstractController
{

    /**
     * @Route (path="/", methods={"GET"})
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
        $holidays = null;

        return $this->render('publicHolidays/search.html.twig', [
            'country' => $request->query->get('country'),
            'year' => $request->query->get('year'),
            'holidays' => $holidays,
        ]);
    }

}