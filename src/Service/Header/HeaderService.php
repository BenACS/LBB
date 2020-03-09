<?php

namespace App\Service\Header;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HeaderService
{
    protected $categoryRepo;
    protected $session;

<<<<<<< HEAD
    public function __construct(CategoryRepository $categoryRepo)
    {
=======
    public function __construct(CategoryRepository $categoryRepo, SessionInterface $session) {
>>>>>>> 37dd191f89869d130ed6d712d338edddfda9b19a
        $this->categoryRepo = $categoryRepo;
        $this->session = $session;
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
<<<<<<< HEAD
}
=======
    
    public function getCartInt():int {

        return count($this->session->get('cart') ?? []);
    }
}
>>>>>>> 37dd191f89869d130ed6d712d338edddfda9b19a
