<?php

namespace App\Repository;

use App\Entity\SujetResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SujetResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method SujetResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method SujetResponse[]    findAll()
 * @method SujetResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SujetResponse::class);
    }

    /**
     * @return SujetResponse[] Returns an array of SujetResponse objects
     */
    public function sujetComment($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sujet = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return SujetResponse[] Returns an array of SujetResponse objects
     */
    public function commentCount($value)
    {
        return $this->createQueryBuilder('c')
        ->select('c.id')
        ->where('c.sujet = :value')
        ->setParameter('value', $value)
        ->getQuery()
        ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?SujetResponse
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
