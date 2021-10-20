<?php

namespace App\Repository;

use App\Entity\ActuImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActuImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActuImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActuImage[]    findAll()
 * @method ActuImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActuImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActuImage::class);
    }

    // /**
    //  * @return ActuImage[] Returns an array of ActuImage objects
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
    public function findOneBySomeField($value): ?ActuImage
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
