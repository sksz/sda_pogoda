<?php

namespace App\Controller;

use App\Handler\ContactHandler;
use App\Handler\ImgwHandler;
use App\Handler\MesurementHandler;
use App\Entity\Mesurement;
use App\Entity\Contact;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
        return $this->render(
            'base.' . $_format . '.twig',
            [
                'app' => [
                    'user' => null,
                ],
                'cities' => $this->mesurementHandler->getCities(),
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
            ->add('name', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class)
            ->getForm();

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
                'cities' => $this->mesurementHandler->getCities(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/cityMesures", defaults={"_format"="html"}, name="cityMesures")
     * @IsGranted("ROLE_USER")
     */
    public function cityMesuresAction(Request $request, string $_format): Response
    {
        $city = $request->query->get('city');

        return $this->render(
            'cityMesurments.' . $_format . '.twig',
            [
                'city' => $city,
                'cities' => $this->mesurementHandler->getCities(),
                'mesures' => $this->mesurementHandler->getCityRecords($city),
            ]
        );
    }

    /**
     * @Route("/findCity", defaults={"_format"="html"}, name="findCity", methods={"POST"})
     */
    public function findCityAction(Request $request, string $_format): Response
    {
        $city = $request->request->get('city');

        try {
            $this->imgwHandler->getData($city);
        } catch (ClientException $exception) {
            return $this->render(
                'noCity.' . $_format . '.twig',
                [
                    'cities' => $this->mesurementHandler->getCities(),
                ]
            );
        }

        return $this->redirectToRoute(
            'weather',
            [
                'city' => $city
            ]
        );
    }

    /**
     * @Route("/weather", defaults={"_format"="html"}, name="weather")
     */
    public function weatherAction(Request $request, string $_format): Response
    {
        $city = $request->query->get('city');

        if (!$this->mesurementHandler->isActualCityRecord($city)) {
            $mesurement = $this->imgwHandler->getData($city);

            $this->mesurementHandler->add($mesurement);

            return $this->renderWeatherView(
                $mesurement,
                $city,
                $this->mesurementHandler->getCities(),
                $_format
            );
        }

        return $this->renderWeatherView(
            $this->mesurementHandler->getActualCityRecord($city),
            $city,
            $this->mesurementHandler->getCities(),
            $_format
        );
    }

    private function renderWeatherView(Mesurement $mesurement, string $city, array $cities, string $_format): Response
    {
        return $this->render(
            'city.' . $_format . '.twig',
            [
                'mesurement' => $mesurement,
                'city' => ucfirst($city),
                'cities' => $cities,
            ]
        );
    }
}
