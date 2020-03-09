<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\Article;
use App\Entity\ArticleImages;

use App\Data\SearchData;
use App\Form\SearchForm;

use App\Service\Header\HeaderService;
use App\Service\Header\TagService;

class SubcategoriesController extends AbstractController
{
    /**
     * @Route("/{category}/{subcategories}", name="subcategories")
     */
    public function index(ProductRepository $productRepo, HeaderService $header, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();

        $products = $productRepo->findSearch();
        
        $articles = $em->getRepository(Article::class)->findAll();
        $articleImages = $em->getRepository(ArticleImages::class)->findAll();

        foreach ( $products as $product) {
            $images[] = $product->getAllUniqueImages()[0];
        }

        // Filter form-related
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        return $this->render('subcategories/index.html.twig', [
            'header' => $header,
            'products' => $products,
            'articleImages' => $images,
            'form' => $form->createView()
        ]);
    }


    public function getProductsData(HeaderService $header, ProductRepository $productRepo)
    {

        $em = $this->getDoctrine()->getManager();

        $products = $productRepo->findSearch();

        $articles = $em->getRepository(Article::class)->findAll();
        $articleImages = $em->getRepository(ArticleImages::class)->findAll();

        foreach ( $products as $product) {
            $images[] = $product->getAllUniqueImages()[0];
        }

        return $this->render('subcategories/index.html.twig', [
            'header' => $header,
            'products' => $products,
            'articleImages' => $images,
        ]);
    }

    
}
