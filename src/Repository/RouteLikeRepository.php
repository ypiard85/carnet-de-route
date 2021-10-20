<?php

namespace App\Repository;

use App\Entity\RouteLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RouteLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouteLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouteLike[]    findAll()
 * @method RouteLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteLike::class);
    }

    /**
     * @return RouteLike[] Returns an array of RouteLike objects
     */
    public function findRouteLikeByUser($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?RouteLike
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
