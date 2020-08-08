<?php

namespace App\Controller;

use App\Handler\ContactHandler;
use App\Handler\ImgwHandler;
use App\Handler\MesurementHandler;
use App\Entity\Mesurement;
use App\Entity\Contact;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BaseController extends AbstractController
{
    private $logger;

    private $imgwHandler;

    private $mesurementHandler;

    private $contactHandler;

    public function __construct(LoggerInterface $logger, ImgwHandler $imgwHandler, MesurementHandler $mesurementHandler, ContactHandler $contactHandler)
    {
        $this->logger = $logger;
        $this->imgwHandler = $imgwHandler;
        $this->mesurementHandler = $mesurementHandler;
        $this->contactHandler = $contactHandler;
    }

    /**
     * @Route("/", defaults={"_format"="html"}, name="index")
     */
    public function indexAction(Request $request, string $_format): Response
    {
        $cities = $this->mesurementHandler->getCities();

        return $this->render(
            'base.' . $_format . '.twig',
            [
                'app' => [
                    'user' => null,
                ],
                'cities' => $cities,
            ]
        );
    }

    /**
     * @Route("/contact", defaults={"_format"="html"}, name="contact")
     */
    public function contactAction(Request $request, string $_format): Response
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
            ->add('replyTo', EmailType::class)
            ->add('content', TextType::class)
            ->add('name', TextType::class)
            ->add('timestamp', DateTimeType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $cities = $this->mesurementHandler->getCities();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactHandler->newContact($contact);
        }

        return $this->render(
            'contact.' . $_format . '.twig',
            [
                'app' => [
                    'user' => null,
                ],
                'cities' => $cities,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/cityMesures", defaults={"_format"="html"}, name="cityMesures")
     */
    public function cityMesuresAction(Request $request, string $_format): Response
    {
        $city = $request->query->get('city');

        $mesures = $this->mesurementHandler->getCityRecords($city);

        $this->logger->info('Pobrane pomiary dla miasta', [
            'city' => $city,
            'mesures' => $mesures,
        ]);

        $cities = $this->mesurementHandler->getCities();

        return $this->render(
            'cityMesurments.' . $_format . '.twig',
            [
                'city' => $city,
                'cities' => $cities,
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
            $cities = $this->mesurementHandler->getCities();

            return $this->render('noCity.' . $_format . '.twig', [
                'cities' => $cities,
            ]);
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

        $cities = $this->mesurementHandler->getCities();

        if (!$this->mesurementHandler->isActualCityRecord($city)) {
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
                $cities,
                $_format
            );
        }

        $cityRecord = $this->mesurementHandler->getActualCityRecord($city);

        return $this->renderWeatherView(
            $this->imgwHandler->parseData($cityRecord->convertToArray()),
            $city,
            $cities,
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

    private function renderWeatherView(array $data, string $city, array $cities, string $_format): Response
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
                'cities' => $cities,
            ]
        );
    }
}
