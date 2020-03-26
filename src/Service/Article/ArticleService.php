<?php

namespace App\Service\Article;

use App\Data\Cart\SelectionArticleData;
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

    public function getArticleInfos(SelectionArticleData $selection) : Article {
        foreach ($selection as $key => $value) {
            if ($value) {
                $criteria[$key] = $value;
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