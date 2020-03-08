<?php

namespace App\Controller;

use App\Repository\ArticleRepository;

use App\Service\Header\HeaderService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/recap", name="cart")
     */
    public function index(HeaderService $header, SessionInterface $session, ArticleRepository $articleRepository)
    {
        if ($session->get('cart')) {
            foreach ($session->get('cart') as $itemId => $quantity) {
                $cart[] = [
                    'article' => $articleRepository->find($itemId),
                    'quantity' => $quantity
                ];
            }
        }
        
        

        return $this->render('cart/index.html.twig', [
            'header' => $header,
            'itemsInCart' => count($cart ?? []),
            'cart' => $cart ?? []
        ]);
    }

    /**
     * Add an article into the cart
     * @Route("/product/addToCart", name="cart_add")

     * @param Request $request
     * @param ArticleRepository $articleRepo
     * @param SessionInterface $session
     * @return Response
     */
    public function addToCart(Request $request, ArticleRepository $articleRepo, SessionInterface $session) : Response {

        $articleId = $request->request->get('articleId');
        $quantity = (int) $request->request->get('quantity');
        $article = $articleRepo->find($articleId);
        $cart = $session->get('cart',[]);

        if (!empty($cart[$articleId])) {
            $cart[$articleId] += $quantity;
        } else {
            $cart[$articleId] = $quantity;
        }
        
        $session->set('cart',$cart);

        return $this->json([
            'title' =>  $article->getArticleTitle(),
            'image' => $article->getProduct()->getAllUniqueImages()[0],
            'itemsInCart' => count($cart),
            'quantity'=>$quantity,
            'sessionCart' => $session
        ],200);
    }

    /**
     * Remove an article from the cart
     * @Route("/product/removeFromCart", name="cart_remove")

     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function removeFromCart(Request $request, SessionInterface $session) : Response {
        $articleId = (int) $request->request->get('articleId');
        $cart = $session->get('cart',[]);

        if (!empty($cart[$articleId])) {
            unset($cart[$articleId]);
        }
        
        $session->set('cart',$cart);
        
        return $this->json([
            'articleId'=> $articleId,
            'itemsInCart' => count($cart)
        ],200);
    }

    /**
     * Modify quantity of the selected article
     * @Route("/product/modifyCart", name="cart_modify")

     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function modifyArticleQuantity(Request $request, SessionInterface $session):Response {
        $articleId = (int) $request->request->get('articleId');
        $quantity = (int) $request->request->get('quantity');
        $cart = $session->get('cart',[]);

        if (!empty($cart[$articleId])) {
            $cart[$articleId] = $quantity;
        }
        
        $session->set('cart',$cart);
        
        return $this->json([
            'articleId'=> $articleId,
            'quantity' => $quantity
        ],200);
    }

    /**
     * Function only for test purpose
     * @Route("/product/showCart", name="cart_show")

     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function giveMeCart(Request $request, SessionInterface $session):Response {
        $cart = $session->get('cart');

        return $this->json([
            'cart' => $cart
        ],200);
    }
}
