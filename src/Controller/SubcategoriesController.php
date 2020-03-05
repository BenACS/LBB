<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubcategoriesController extends AbstractController
{
    /**
     * @Route("/subcategories", name="subcategories")
     */
    public function index()
    {
        return $this->render('subcategories/index.html.twig', [
            'controller_name' => 'SubcategoriesController',
        ]);
    }
}
