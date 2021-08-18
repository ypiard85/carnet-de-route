<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Actualites;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Actualites|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actualites|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actualites[]    findAll()
 * @method Actualites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actualites::class);
    }

    /**
    * @return Actualites[] Returns an array of Actualiter objects
    */
    public function finadAllActualites()
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()

        ;

    }
}
