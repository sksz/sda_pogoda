<?php

namespace App\Handler;

use App\Entity\Mesurement;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class MesurementHandler
{
    private $logger;

    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function add(Mesurement $mesurement)
    {
        $this->entityManager->persist($mesurement);
        $this->entityManager->flush();
    }
}
