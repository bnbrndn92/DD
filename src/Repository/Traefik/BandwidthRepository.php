<?php

namespace App\Repository;

use App\Entity\Traefik\Bandwidth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Bandwidth|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bandwidth|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bandwidth[]    findAll()
 * @method Bandwidth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandwidthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bandwidth::class);
    }

    // /**
    //  * @return Bandwidth[] Returns an array of Bandwidth objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bandwidth
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
