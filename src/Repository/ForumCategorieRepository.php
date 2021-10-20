<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\ForumCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCategorie[]    findAll()
 * @method ForumCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumCategorie::class);
    }




    public function categoriFilter(SearchData $searchdata): ?ForumCategorie
    {
        $query = $this->createQueryBuilder('f')
                ->select('f')
                ->join('f.sujets', 's');

                if($searchdata->forum == 'sujet')
                {
                    $query = $query
                        ->select('s')
                        ->orderBy('s.id', 'DESC');
                }


        return $query->getQuery()->getResult();
    }

}
