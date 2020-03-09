<?php

namespace App\Service\Header;

use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HeaderService
{
    protected $categoryRepo;
    protected $session;

    public function __construct(CategoryRepository $categoryRepo, SessionInterface $session, TagRepository $tagRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->tagRepo = $tagRepo;
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

    public function getCatId($name): int {
        return $this->categoryRepo->findOneBy(['categoryName'=>$name])->getId();
    }

    public function getCartInt(): int
    {

        return count($this->session->get('cart') ?? []);
    }

    public function getTagNames(): string
    {
        foreach ($this->tagRepo->findAll() as $tags) {
            $tagName[] = $tags->getTagName();
        }

        return implode(",", $tagName);
    }
    public function getTagNamesArray(): array
    {
        foreach ($this->tagRepo->findAll() as $tags) {
            $tagName[] = $tags->getTagName();
        }

        return $tagName;
    }

    public function getTagCategory(int $id)
    {
        return $this->tagRepo->find($id)->getCategory();
    }
}
