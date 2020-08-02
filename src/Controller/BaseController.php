<?php

namespace App\Controller;

use App\Handler\ImgwHandler;
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

    public function __construct(LoggerInterface $logger, ImgwHandler $imgwHandler)
    {
        $this->logger = $logger;
        $this->imgwHandler = $imgwHandler;
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

        return $this->renderWeatherView(
            $this->imgwHandler->getData($request->query->get('city')),
            $city,
            $_format
        );
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
