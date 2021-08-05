<?php

namespace App\Repository;

use App\Entity\Actualiter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Actualiter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actualiter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actualiter[]    findAll()
 * @method Actualiter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualiterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actualiter::class);
    }

    // /**
    //  * @return Actualiter[] Returns an array of Actualiter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Actualiter
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
