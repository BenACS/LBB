<?php

namespace App\Service\Header;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class HeaderService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getCategories(): array
    {

        return $this->categoryRepo->findAll();
    }

    public function getMainCatName(int $parentId): string
    {
        if ($parentId == 0) {
            return "";
        }

        return $this->categoryRepo->find($parentId)->getCategoryName();
    }
}
