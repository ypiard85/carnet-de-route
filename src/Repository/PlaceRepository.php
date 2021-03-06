<?php

namespace App\Repository;

use App\Entity\Place;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,  PaginatorInterface $paginator)
    {
        parent::__construct($registry, Place::class);

        $this->paginator = $paginator;
    }

    public function findPlaceByUser(){

             $this->createQueryBuilder('p')
                ->getQuery()
                ->getResult()
                ;
    }

    public function findByVille(){
        $this->createQueryBuilder('p')
        ->getQuery()
        ->getResult()
        ;
    }



    /**
     * @return PaginationInterface
     *
     *
     *
     */
    public function findSearch(SearchData $search): PaginationInterface
    {

        $query = $this->createQueryBuilder('p')
                ->select('p')
                ->join('p.city', 'c' )
                ->join('p.categorie', 't' )
                ;

                if(!empty($search->q)){
                    $query = $query
                    ->andWhere('p.title LIKE :q')
                    //->orWhere('c.name LIKE :q')
                    ->setParameter('q', "%{$search->q}%" );
                }

                if(!empty($search->city)){
                    $query = $query
                    ->andWhere('c.name  LIKE :city')
                    ->setParameter('city', $search->city );
                }

                if(!empty($search->categorie)){
                    $query = $query
                    ->andWhere('t.nom LIKE :categorie')
                    ->setParameter('categorie', "%{$search->categorie}%");
                }

                if(!empty($search->premium)){
                    $query = $query
                    ->andWhere('p.premium LIKE :premium')
                    ->setParameter('premium', "%{$search->premium}%");
                }


                    if(!empty($search->filter)){
                        switch ($search->filter) {
                            case 'az':
                                $query = $query->orderBy('p.title', 'ASC');
                                break;
                            case 'za':
                                $query = $query->orderBy('p.title', 'DESC');
                                break;
                            default:
                            $query = $query->orderBy('p.title', 'ASC');
                                break;
                        }
                    }else{
                        $query = $query->orderBy('p.id', 'DESC');
                    }

          $query = $query->getQuery();
          return  $this->paginator->paginate($query, $search->page, 20);

        }


}
