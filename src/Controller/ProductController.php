<?php

namespace App\Controller;

use App\Entity\Price;
use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ArticleImages;

use App\Service\Header\HeaderService;
use App\Service\Article\ArticleService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product", requirements={"id"="\d+"})
     */
    public function index(int $id = 0, Product $product = null, HeaderService $cat, ArticleService $article){

        if ($id == 0 || !isset($product)) {
            return $this->redirectToRoute("home");
        }
        $test = $article->getArticleInfos($product->getId());

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'price' => $product->getPrice(),
            'variations' => ['sizes' => $product->getAllSizes(), 'colors' => $product->getAllColors(), 'devices' => $product->getAllDevices()],
            'articleImages' => $product->getAllUniqueImages(),
            'categories' => $cat->createHeader(),
            'article'=> $product->getArticles()[0],
            'test'=> $test
        ]);
    }

    /**
     * Check which article was selected
     * @Route("/product/{id}/checkArticle", name="product_checkArticle", requirements={"id"="\d+"})

     * @param integer $id
     * @param ArticleService $articleService
     * @return Response
     */
    public function checkArticle(int $id, Request $request, ArticleService $articleService) : Response {

        $article = $articleService->getArticleInfos($id,$request->request->all());

        return $this->json([
            'articleId'=> $article->getId(),
            'stock' => $article->getStockMessage(),
            'request' => $request->request->all()   
            ],200);
    }
}
