<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\ForumCategorie;
use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sujet[]    findAll()
 * @method Sujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

    /**
    * @return Sujet[] Returns an array of Sujet objects
    *
    */
       public function findSujetByCategorie($value)
    {

        return $this->createQueryBuilder('s')
            ->where('s.categorie = :val ' )
            ->setParameter('val', $value)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Sujet[] Return an array of Sujet Objects
     */
    public function findAllSujet(SearchData $search){

        $query = $this->createQueryBuilder('s')
                ->select('s')
                ->orderBy('s.createdAt', 'ASC');

                if(!empty($search->sujet)){
                    $query = $query
                    ->andWhere('s.title LIKE :sujet')
                    ->setParameter('sujet', "%{$search->sujet}%" );
                }

        return $query->getQuery()->getResult();

    }


    public function countSujetByCategorie($value)
    {


        return $this->createQueryBuilder('c')
        ->where('c.categorie = :value'  )
        ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
        ;
    }




    /*
    public function findOneBySomeField($value): ?Sujet
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
