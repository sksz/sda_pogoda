<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserHandler
{
    private $entityManager;

    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function newUser(User $user)
    {
        $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);
        $user->setRole('["ROLE_USER"]');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
