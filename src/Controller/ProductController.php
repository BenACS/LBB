<?php

namespace App\Controller;

use App\Entity\Price;
use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\Category;
use App\Entity\ArticleImages;

use App\Service\Header\HeaderService;
use App\Service\Header\TagService;
use App\Service\Article\ArticleService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id?0}", name="product", requirements={"id"="\d+"})
     */
    public function index(int $id = 0, Product $product = null, HeaderService $header, ArticleService $article)
    {

        if ($id == 0 || !isset($product)) {
            return $this->redirectToRoute("home");
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'price' => $product->getPrice(),
            'variations' => ['sizes' => $product->getAllSizes(), 'colors' => $product->getAllColors(), 'devices' => $product->getAllDevices()],
            'articleImages' => $product->getAllUniqueImages(),
            'header' => $header,
            'article' => $product->getArticles()[0]
        ]);
    }

    /**
     * Check which article was selected
     * @Route("/product/{id}/checkArticle", name="product_checkArticle", requirements={"id"="\d+"})

     * @param integer $id
     * @param ArticleService $articleService
     * @return Response
     */
    public function checkArticle(int $id, Request $request, ArticleService $articleService): Response
    {

        $article = $articleService->getArticleInfos($id, $request->request->all());

        return $this->json([
            'articleId' => $article->getId(),
            'stockMessage' => $article->getStockMessage(),
            'stock' => $article->getStock()
        ], 200);
    }
}
