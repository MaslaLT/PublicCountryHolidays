<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class PchApi
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function get(){
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://kayaposoft.com/enrico/json/v2.0/?action=getHolidaysForYear&year=2019&country=ltu&holidayType=all');
    }

    public function getSupportedCountries(): array
    {
        $httpClient = HttpClient::create();
        $response = $httpClient
            ->request('GET', $this->url . 'getSupportedCountries');

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Failed to create a request');
        } else {
            return $response->toArray();
        }
    }
}