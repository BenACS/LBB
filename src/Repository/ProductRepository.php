<?php

namespace App\Repository;

use App\Entity\Product;
use App\Data\SearchData;
use Doctrine\ORM\QueryBuilder;

use Doctrine\ORM\Query\Expr\Join;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */

    public function findLatestProducts()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findHottestProducts()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     *  Get products related to a search
     *  @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            8
        );
    }

    /**
     * Get min and max prices related to a search
     * @param SearchData $search
     * @return integer[]
     */
    public function findMinMax(SearchData $search) :array {
        
        $results = $this->getSearchQuery($search, true)
            ->select('MIN(price.priceDf) as min', 'MAX(price.priceDf) as max')
            ->getQuery()
            ->getScalarResult();
            ;
        return [$results[0]['min'],$results[0]['max']];
    }

    private function getSearchQuery(SearchData $search, $ignorePrice = false) : QueryBuilder
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c')
            ->leftjoin('p.price','price')
            ;

        if(!empty($search->min) && $ignorePrice === false){
            $query = $query
                ->andWhere('price.priceDf >= :min')
                ->setParameter('min', $search->min);
        }

        if(!empty($search->max) && $ignorePrice === false){
            $query = $query
                ->andWhere('price.priceDf <= :max')
                ->setParameter('max', $search->max);
        }

        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        return $query;
    }
  
}
