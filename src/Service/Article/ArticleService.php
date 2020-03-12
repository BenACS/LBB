<?php

namespace App\Service\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;

class ArticleService {
    protected $articleRepo;
    protected $imageRepo;
    protected $productRepo;

    public function __construct(ArticleRepository $articleRepo, ImageRepository $imageRepo, ProductRepository $productRepo) {
        $this->articleRepo = $articleRepo;
        $this->imageRepo = $imageRepo;
        $this->productRepo = $productRepo;
    }

    public function getArticleInfos(int $productId, array $selectionsAssoc = null) : Article {
        $criteria = ['product'=> $productId];

        if ($selectionsAssoc) {
            for ($i = 0 ; $i < count($selectionsAssoc) ; $i++) {
                $column = array_keys($selectionsAssoc)[$i];
                if ($selectionsAssoc[$column] && $selectionsAssoc[$column] != "null" ) {
                    $criteria[$column] = $selectionsAssoc[$column];
                }
            }
        }
        
        return $this->articleRepo->findOneBy($criteria, ['id'=>'ASC']);
    }

    /**
     * Get all the unique images with the productId
     *
     * @param integer $productId
     * @return Image[]|null
     */
    public function getAllImages(int $productId) : ?array {
        return $this->imageRepo->findByProduct($productId);
    }

    /**
     * Get the latest products
     *
     * @return Product[]|null
     */
    public function getLatestProducts() : ? array {
        return $this->productRepo->findLatestProducts();
    }

    /**
     * Find the hottest products (currently just catching the 8 first products)
     *
     * @return Product[]|null
     */
    public function getHottestProducts() : ? array {
        return $this->productRepo->findHottestProducts();
    }
}