<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Header\HeaderService;

class SubcategoriesController extends AbstractController
{
    /**
     * @Route("/{category}/{subcategories}", name="subcategories")
     */
    public function index(HeaderService $header)
    {
        return $this->render('subcategories/index.html.twig', [
            'controller_name' => 'SubcategoriesController',
            'header' => $header
        ]);
    }
}
