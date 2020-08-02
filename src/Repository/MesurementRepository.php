<?php

namespace App\Repository;

use App\Entity\Mesurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mesurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mesurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mesurement[]    findAll()
 * @method Mesurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MesurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mesurement::class);
    }

    /**
     * @return Mesurement[] Returns an array of Mesurement objects
     */
    public function findByCityDate(string $city, \DateTime $newerThan): array
    {
        return $this->createQueryBuilder('mesurement')
            ->andWhere('mesurement.city = :city')
            ->andWhere('mesurement.timestamp >= :timestamp')
            ->setParameter('city', $city)
            ->setParameter('timestamp', $newerThan)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Mesurement
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
