<?php

namespace App\Service\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticleService {
    protected $articleRepo;

    public function __construct(ArticleRepository $articleRepo) {
        $this->articleRepo = $articleRepo;
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

    
}