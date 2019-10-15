<?php

namespace App\Repository;

use App\Entity\Traefik\DatabaseUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DatabaseUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatabaseUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatabaseUser[]    findAll()
 * @method DatabaseUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatabaseUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatabaseUser::class);
    }

    // /**
    //  * @return DatabaseUser[] Returns an array of DatabaseUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatabaseUser
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
