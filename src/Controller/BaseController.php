<?php

namespace App\Controller;

use App\Handler\ImgwHandler;
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
     * @Route("/szczecin", defaults={"_format"="html"}, name="szczecin")
     */
    public function szczecinAction(Request $request, string $_format): Response
    {
        $weatherData = $this->imgwHandler->getData('szczecin');

        return $this->render(
            'city.' . $_format . '.twig',
            [
                'temperature' => $weatherData['temperatura'],
                'windSpeed' => $weatherData['predkosc_wiatru'],
                'windDirectionDescription' => $weatherData['kierunek_wiatru_opis'],
                'windDirectionDegrees' => $weatherData['kierunek_wiatru_stopnie'],
                'pressure' => $weatherData['cisnienie'],
            ]
        );
    }
}
