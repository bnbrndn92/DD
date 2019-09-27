<?php

namespace App\Repository;

use App\Entity\Frontend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Frontend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Frontend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Frontend[]    findAll()
 * @method Frontend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrontendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Frontend::class);
    }

    /**
     * findByFrontendName()
     *
     * @param string $value
     *
     * @return mixed
     */
    public function findByFrontendName (string $value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.name = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Frontend[] Returns an array of Frontend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Frontend
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
