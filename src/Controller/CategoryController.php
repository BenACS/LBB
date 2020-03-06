<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;
use App\Repository\CategoryRepository;

use App\Service\Header\HeaderService;

class CategoryController extends AbstractController
{
    /**
     * @Route("/{category}", name="category")
     */
    public function index(HeaderService $cat)
    {

        return $this->render('category/index.html.twig', [
            'categories' => $cat->createHeader(),
            'test' => $cat->getMainCategoriesName()
        ]);
        
    }
}
