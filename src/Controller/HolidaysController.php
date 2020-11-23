<?php

namespace App\Controller;

use App\Entity\SupportedCountryList;
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
        $countryList = $this->getDoctrine()
            ->getRepository(SupportedCountryList::class)
            ->findAll();

        if (empty($countryList)) {
            $countryList = $pchApi->getSupportedCountries();
        }

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
        //$pchApi->get();

        return $this->render('publicHolidays/search.html.twig', [
            'country' => $request->query->get('country'),
            'year' => $request->query->get('year'),
            'holidays' => $holidays,
        ]);
        //        $data = new HolidayRepository();
        //        $httpClient = HttpClient::create();
        //        $response = $httpClient->request('GET', 'https://kayaposoft.com/enrico/json/v2.0/?action=getHolidaysForYear&year=2019&country=ltu&holidayType=all');
        //        dump($response->toArray());
        //        die();
        //        return $this->json($response->getContent());
    }

}