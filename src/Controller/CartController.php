<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Product;
use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $order = new Order();

        $products = array();
        if($session->get('cart')) {
            foreach ($session->get('cart') as $cart){
                $product = $em->getRepository(Product::class)->find($cart);
                $products[] = $product;
                $order->addProduct($product);
            }
        }
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);
        if($request->isMethod('POST') && $form->isValid()){
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
}
