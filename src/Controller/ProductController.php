<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\Price;
use App\Entity\ArticleImages;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Header\HeaderService;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product", requirements={"id"="\d+"})
     */
    public function index(int $id = 0, Product $product = null, HeaderService $cat)
    {

        if ($id == 0 || !isset($product)) {
            return $this->redirectToRoute("home");
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'price' => $product->getPrice(),
            'variations' => ['sizes' => $product->getAllSizes(), 'colors' => $product->getAllColors(), 'devices' => $product->getAllDevices()],
            'articleImages' => $product->getAllUniqueImages(),
            'categories' => $cat->createHeader(),
            'stock' => $product->getArticles()[0]->getStockMessage(),
            'articleId' => $product->getArticles()[0]->getId()
        ]);
    }

    /**
     * @Route("/product/{id}/checkstock", name="product_checkstock", requirements={"id"="\d+"})
     */
    // public function checkStock(ArticleRepository $articleRepo) {

    // }
}
