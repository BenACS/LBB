<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Header\HeaderService;
use App\Service\Header\TagService;

class SubcategoriesController extends AbstractController
{
    /**
     * @Route("/{category}/{subcategories}", name="subcategories")
     */
    public function index(HeaderService $header, TagService $tag)
    {
        return $this->render('subcategories/index.html.twig', [
            'controller_name' => 'SubcategoriesController',
            'header' => $header,
            'tags' => $tag->getTagNames()
        ]);
    }
}
