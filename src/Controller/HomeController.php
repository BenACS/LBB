<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ArticleImages;

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
    	$articleImages = $em->getRepository(ArticleImages::class)->findAll();
    	
    	dump($articleImages);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'products' => $products,
            'articleImages' => $articleImages
        ]);
    }
}
