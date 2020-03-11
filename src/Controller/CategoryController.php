<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

use App\Data\SearchData;
use App\Form\SearchForm;

use App\Service\Header\HeaderService;
use App\Service\Header\TagService;

class CategoryController extends AbstractController
{
    

    /**
    * @Route("/Surprises", name="surprises")
    */
    public function surprisesPage( ProductRepository $productRepo, HeaderService $header, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();

        $data = new SearchData($header->getCatByName('Surprises')); // Filter form-related
        $data->page = $request->get('page',1);

        // Filter form-related
        $form = $this->createForm(SearchForm::class, $data, $options = ['catId'=>$header->getCatByName('Surprises')->getId()]);
        $form->handleRequest($request);

        // Prices search-related
        [$min, $max] = $productRepo->findMinMax($data);

        $products = $productRepo->findSearch($data);

        return $this->render('subcategories/index.html.twig', [
            'header' => $header,
            'products' => $products,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max
        ]);
    }

    /**
     * @Route("/{category}", name="category")
     */
    public function index(HeaderService $header)
    {

        return $this->render('category/index.html.twig', [
            'header' => $header
        ]);
    }
}
