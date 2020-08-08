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

    public function getCityRecords(string $city)
    {
        $mesurements = $this
            ->entityManager
            ->getRepository(Mesurement::class)
            ->findBy([
                'city' => $city,
            ]);

        return $mesurements;
    }

    public function isActualCityRecord(string $city)
    {
        $date = new \DateTime('NOW');
        $date->sub(new \DateInterval('PT' . 60 * 5 . 'S'));

        $mesurements = $this
            ->entityManager
            ->getRepository(Mesurement::class)
            ->findByCityDate($city, $date);

        return !empty($mesurements);
    }

    public function getActualCityRecord(string $city)
    {
        $date = new \DateTime('NOW');
        $date->sub(new \DateInterval('PT' . 60 * 5 . 'S'));

        $mesurement = $this
            ->entityManager
            ->getRepository(Mesurement::class)
            ->findOneByCityDate($city, $date);

        return $mesurement;
    }

    public function getCities(): array
    {
        $cities = $this
            ->entityManager
            ->getRepository(Mesurement::class)
            ->findCities();

        $result = [];

        foreach ($cities as $column => $city) {
            $result[] = ucfirst($city['city']);
        }

        $this->logger->info('Pobrane miasta', [
            'cities' => serialize($result)
            ]);

        return $result;
    }
}
