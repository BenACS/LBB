<?php

namespace App\Service\Header;

use App\Entity\Tag;
use App\Repository\TagRepository;

class TagService
{
    protected $categoryRepo;

    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }

    public function getTagNames(): array
    {
        foreach ($this->tagRepo->findAll() as $tags) {
            $tagName[] = $tags->getTagName();
        }
        return $tagName;
    }
}
