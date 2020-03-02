<?php

namespace App\Repository;

use App\Entity\ArticleImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArticleImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleImages[]    findAll()
 * @method ArticleImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleImages::class);
    }


    @return ArticleImages[] Returns an array of ArticleImages objects
  
    
    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('a.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?ArticleImages
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
