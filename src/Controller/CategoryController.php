<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ArticleImages;
use App\Entity\Article;

use App\Service\Header\HeaderService;

class CategoryController extends AbstractController
{
    /**
     * @Route("/{category}", name="category")
     */
    public function index(HeaderService $cat, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository(Product::class)->findAll();
        $article = $em->getRepository(Article::class)->findAll();
        $articleImages = $em->getRepository(ArticleImages::class)->findAll();

        // Lets us filter for the URLs
        foreach ( $products as $product) {
            foreach ( $product -> getArticles() as $article) {
                foreach ( $article -> getArticleImages() as $image) {
                    if (!isset ($images) || !in_array($image->getUrl(),$images)) {
                        $images[] = $image->getUrl();
                    }
                }
            }
        }

        // $request->request->get('id');
        
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'articleImages' => $images,
            'categories' => $cat->createHeader()
        ]);
        
    }
}
