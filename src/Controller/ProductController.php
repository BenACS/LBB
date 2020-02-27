<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product")
     */
    public function index(Product $product, Request $request)
    {
        $session = new Session();

        if($request->query->get('addCart')){
            // ajout au panier
            $productId = $product->getId();
            $cart = ($session->get('cart') != null) ? $session->get('cart') : [];
            $cart[] = $productId;
            $session->set('cart', $cart);

            return $this->redirectToRoute('product', array('id' => $productId));
        }
        return $this->render('product/index.html.twig', [
            'product' => $product
        ]);
    }
    // VERSION SANS INJECTION DE DEPENDANCE
    // public function index($id)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $product = $em->getRepository(Product::class)->find($id);

    //     return $this->render('product/index.html.twig', [
    //         'product' => $product
    //     ]);
    // }
}
