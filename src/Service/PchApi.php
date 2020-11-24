<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class PchApi
{
    private const API_BASE_URL = 'https://kayaposoft.com/enrico/json/v2.0/?action=';

    private $pchApiCacheManager;

    public function __construct(PchApiCacheManager $pchApiCacheManager)
    {
        $this->pchApiCacheManager = $pchApiCacheManager;
    }

    public function fetchApi($url){
        $cache = $this->pchApiCacheManager->loadFromCache($url);

        if (!empty($cache)) {
            return $cache->getResponse();
        }

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $url);
        if(!200 == $response->getStatusCode()) {
            throw new \Exception(
                'Encountered error fetching ' . $url
            );
        }

        $this->pchApiCacheManager->addToCache($url, $response->toArray());
        return $response->toArray();
    }

    public function getHolidaysForYear(string $countryCode, string $year, string $type = 'all')
    {
        $url = self::API_BASE_URL .
            'getHolidaysForYear&year=' . $year .
            '&country=' . $countryCode .
            '&holidayType=' . $type;

        return $this->fetchApi($url);
    }

    public function getSupportedCountries(): array
    {
        $url = self::API_BASE_URL . 'getSupportedCountries';
        return $this->fetchApi($url);
    }
}