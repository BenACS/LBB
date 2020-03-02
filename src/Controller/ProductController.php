<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Header\HeaderService;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product")
     */
    public function index(Product $product,HeaderService $cat){
        $categories = $cat->createHeader();

        return $this->render('product/index.html.twig', [
            'product' => $product, 'categories' => $categories
        ]);
    }
}