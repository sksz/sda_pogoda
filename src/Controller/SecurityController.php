<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\MesurementHandler;
use App\Handler\UserHandler;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrengthValidator;

class SecurityController extends AbstractController
{
    private $userHandler;

    private $mesurementHandler;

    private $logger;

    public function __construct(LoggerInterface $logger, MesurementHandler $mesurementHandler, UserHandler $userHandler)
    {
        $this->logger = $logger;
        $this->mesurementHandler = $mesurementHandler;
        $this->userHandler = $userHandler;
    }

    /**
     * @Route("/users", defaults={"_format"="html"}, name="users")
     */
    public function usersAction(Request $request, string $_format): Response
    {
        $cities = $this->mesurementHandler->getCities();

        return $this->render(
            'users.' . $_format . '.twig',
            [
                'cities' => $cities,
            ]
        );
    }

    /**
     * @Route("/users/register", defaults={"_format"="html"}, name="register_user")
     */
    public function registerUserAction(Request $request, string $_format): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length(['min' => 4]),
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $cities = $this->mesurementHandler->getCities();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userHandler->newuser($user);
            } catch (UniqueConstraintViolationException $excpetion) {
                return $this->render(
                    'registeredUserExists.' . $_format . '.twig',
                    [
                        'cities' => $cities,
                        'form' => $form->createView(),
                    ]
                );
            }
        }

        return $this->render(
            'registerUser.' . $_format . '.twig',
            [
                'cities' => $cities,
                'form' => $form->createView(),
            ]
        );
    }
}
