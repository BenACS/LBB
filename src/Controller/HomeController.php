<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ArticleImages;
use App\Entity\Article;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

    	$em = $this->getDoctrine()->getManager();

    	$categories = $em->getRepository(Category::class)->findAll();
    	$products = $em->getRepository(Product::class)->findAll();
        $article = $em->getRepository(Article::class)->findAll();
    	$articleImages = $em->getRepository(ArticleImages::class)->findAll();
        
        foreach ( $products as $product) {
            foreach ( $product -> getArticles() as $article) {
                foreach ( $article -> getArticleImages() as $image) {
                    if (!isset ($images) || !in_array($image->getUrl(),$images)) {
                        $images[] = $image->getUrl();
                    }
                }
            }
        }

        dump($images);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'products' => $products,
            'articleImages' => $articleImages
        ]);
    }
}
