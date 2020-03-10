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

use App\Entity\Category;

use App\Service\Header\HeaderService;
use App\Service\Header\TagService;

class SubcategoriesController extends AbstractController
{
    /**
     * @Route("/{category}/{subcategories}", name="subcategories")
     */
    public function index(string $category, string $subcategories, ProductRepository $productRepo, HeaderService $header, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();

        $data = new SearchData($header->getCatByName($subcategories)); // Filter form-related
        $data->page = $request->get('page',1);
        
        // Filter form-related
        $form = $this->createForm(SearchForm::class, $data, $options = ['catId'=>$header->getCatByName($category)->getId()]);
        $form->handleRequest($request);

        [$min,$max] = $productRepo->findMinMax($data);

        $products = $productRepo->findSearch($data);

        return $this->render('subcategories/index.html.twig', [
            'header' => $header,
            'mainCat' => $header->getCatByName($category),
            'products' => $products,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max
        ]);
    }

    
}
