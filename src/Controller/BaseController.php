<?php

namespace App\Controller;

use App\Handler\ImgwHandler;
use App\Handler\MesurementHandler;
use App\Entity\Mesurement;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    private $logger;

    private $imgwHandler;

    private $mesurementHandler;

    public function __construct(LoggerInterface $logger, ImgwHandler $imgwHandler, MesurementHandler $mesurementHandler)
    {
        $this->logger = $logger;
        $this->imgwHandler = $imgwHandler;
        $this->mesurementHandler = $mesurementHandler;
    }

    /**
     * @Route("/", defaults={"_format"="html"}, name="index")
     */
    public function indexAction(Request $request, string $_format): Response
    {
        return $this->render(
            'base.' . $_format . '.twig',
            [
                'app' => [
                    'user' => null,
                ],
            ]
        );
    }

    /**
     * @Route("/cityMesures", defaults={"_format"="html"}, name="cityMesures")
     */
    public function cityMesuresAction(Request $request, string $_format): Response
    {
        $mesures = $this->mesurementHandler->getCityRecords('szczecin');

        $this->logger->info('mesures', ['mesures' => $mesures]);

        return $this->render(
            'cityMesurments.' . $_format . '.twig',
            [
                'city' => 'Szczecin',
                'mesures' => $mesures,
            ]
        );
    }

    /**
     * @Route("/findCity", defaults={"_format"="html"}, name="findCity", methods={"POST"})
     */
    public function findCityAction(Request $request, string $_format): Response
    {
        $this->logger->info('Wyszukaj miasta', [
            'city' => $request->request->get('city'),
        ]);

        $city = $request->request->get('city');

        try {
            $this->imgwHandler->getData($city);
        } catch (ClientException $exception) {
            return $this->render('noCity.' . $_format . '.twig');
        }

        return $this->redirectToRoute('weather', ['city' => $city]);
    }

    /**
     * @Route("/weather", defaults={"_format"="html"}, name="weather")
     */
    public function weatherAction(Request $request, string $_format): Response
    {
        $city = $request->query->get('city');

        $this->logger->info('Wyświetl pogodę dla miasta', [
            'city' => $city,
        ]);

        $cityRecords = $this->mesurementHandler->getCityRecords($city);

        if (empty($cityRecords)) {
            $mesurementData = $this->imgwHandler->getData($city);
            $this->mesurementHandler->add(
                $this->createMesurementEntity(
                    $mesurementData,
                    $city
                )
            );

            return $this->renderWeatherView(
                $mesurementData,
                $city,
                $_format
            );
        }

        $mesurementData = $cityRecords[0]->convertToArray();
        $mesurementData = $this->imgwHandler->parseData($mesurementData);

        return $this->renderWeatherView(
            $mesurementData,
            $city,
            $_format
        );
    }

    private function createMesurementEntity(array $mesurementData, string $city): Mesurement
    {
        $mesurementEntity = new Mesurement();
        $mesurementEntity
            ->setCity($city)
            ->setTemperature($mesurementData['temperatura'])
            ->setWindSpeed($mesurementData['predkosc_wiatru'])
            ->setWindDirection($mesurementData['kierunek_wiatru_stopnie'])
            ->setPressure($mesurementData['cisnienie'])
            ->setTimestamp(new \DateTime('NOW'));

        return $mesurementEntity;
    }

    private function renderWeatherView(array $data, string $city, string $_format): Response
    {
        return $this->render(
            'city.' . $_format . '.twig',
            [
                'temperature' => $data['temperatura'],
                'windSpeed' => $data['predkosc_wiatru'],
                'windDirectionDescription' => $data['kierunek_wiatru_opis'],
                'windDirectionDegrees' => $data['kierunek_wiatru_stopnie'],
                'pressure' => $data['cisnienie'],
                'city' => ucfirst($city),
            ]
        );
    }
}
