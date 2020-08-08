<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function newuser(User $user)
    {
        $user->setTimestamp(new \DateTime('NOW'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
