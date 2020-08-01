<?php

namespace App\Handler;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class ImgwHandler
{
    const IMGW_BASE_URL = 'https://danepubliczne.imgw.pl/api/data/synop/station/';

    private $client;

    private $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getData(string $city): array
    {
        $this->logger->info(
            'Pobieranie danych o pogodzie z wybranego miasta',
            compact('city')
        );

        $response = $this->client->request(
            'GET',
            self::IMGW_BASE_URL . $city,
        );

        if ($response->getStatusCode() !== 200) {
            $this->logger->error(
                'Błąd pobierania danych o pogodzie w mieście',
                compact('city')
            );

            throw new \Exception('Błąd pobierania danych');
        }

        $this->logger->info(
            'Pobrane dane o pogodzie w mieście',
            compact('city', 'response')
        );

        return $this->parseData(
            json_decode(
                $response->getBody(),
                true
            )
        );
    }

    private function parseData(array $data): array
    {
        $data['kierunek_wiatru_opis'] = $this->getWindDirection($data['kierunek_wiatru']);
        $data['kierunek_wiatru_stopnie'] = $data['kierunek_wiatru'];

        return $data;
    }

    private function getWindDirection(int $windDirectionInDegrees): string
    {
        if ($windDirectionInDegrees > 315 || $windDirectionInDegrees < 45) {
            return 'N';
        }

        if ($windDirectionInDegrees >= 45 && $windDirectionInDegrees <= 135) {
            return 'E';
        }

        if ($windDirectionInDegrees >= 135 && $windDirectionInDegrees <= 225) {
            return 'S';
        }

        if ($windDirectionInDegrees >= 225 && $windDirectionInDegrees <= 315) {
            return 'W';
        }
    }
}
