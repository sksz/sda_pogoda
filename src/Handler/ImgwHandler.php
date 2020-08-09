<?php

namespace App\Handler;

use App\Entity\Mesurement;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class ImgwHandler
{
    const IMGW_BASE_URL = 'https://danepubliczne.imgw.pl/api/data/synop/station/';

    const LETTERS_CONVERSION = [
        '&#261;' => 'a',
        '&#347;' => 's',
        '&#263;' => 'c',
        '&#281;' => 'e',
        '&#380;' => 'z',
        '&#378;' => 'z',
        '&#324;' => 'n',
        '&oacute;' => 'o',
        '&#322;' => 'l',
    ];

    private $client;

    private $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getData(string $city): Mesurement
    {
        $this->logger->info(
            'Pobieranie danych o pogodzie z wybranego miasta',
            compact('city')
        );

        $noPolishCharactersCity = $this->noPolishCharacters($city);

        $response = $this->client->request(
            'GET',
            self::IMGW_BASE_URL . $noPolishCharactersCity,
            [
                'verify' => false,
            ]
        );

        if ($response->getStatusCode() !== 200) {
            $this->logger->error(
                'Błąd pobierania danych o pogodzie w mieście',
                compact('city')
            );

            throw new \Exception('Błąd pobierania danych');
        }

        $responseBody = json_decode($response->getBody(), true);

        $this->logger->info(
            'Pobrane dane o pogodzie w mieście',
            [
                'city' => $city,
                'response' => $responseBody,
            ]
        );

        return (new Mesurement())
            ->fromRowResponse(
                $responseBody,
                $city
            )
        ;
    }

    private function noPolishCharacters(string $city)
    {
        $city = strtolower($city);

        $city = mb_convert_encoding($city, "HTML-ENTITIES", "UTF-8");

        foreach (self::LETTERS_CONVERSION as $fromLetter => $toLetter) {
            $city = str_replace($fromLetter, $toLetter, $city);
        }

        return $city;
    }
}
