<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ArticleImages;
use App\Entity\Article;

use App\Repository\CategoryRepository;
use App\Service\Header\HeaderService;
use App\Service\Header\TagService;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(HeaderService $header, CategoryRepository $catRepo)
    {

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository(Product::class)->findAll();
        $articles = $em->getRepository(Article::class)->findAll();
        $articleImages = $em->getRepository(ArticleImages::class)->findAll();
        $latestProducts = $em->getRepository(Product::class)->findLatestsProducts(array('id'));

        // Lets us filter for the URLs
        foreach ($products as $product) {
            $images[] = $product->getAllUniqueImages()[0];
        }

        foreach ($latestProducts as $product) {
            $latestImages[] = $product->getAllUniqueImages()[0];
        }

        // $latestImages = array_reverse($images);

        return $this->render('home/index.html.twig', [
            'header' => $header,
            'products' => $products,
            'articleImages' => $images,
            'latestProducts' => $latestProducts,
            'latestImages' => $latestImages
        ]);
    }
}
