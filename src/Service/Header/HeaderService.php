<?php

namespace App\Service\Header;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class HeaderService {
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

    public function createHeader() : array {
        $categories = $this->categoryRepo->findAll();
        
        return $categories;
    }
}