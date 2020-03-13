<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\Orders;

use App\Entity\Account;
use App\Repository\CartRepository;

use App\Repository\OrdersRepository;
use App\Repository\ArticleRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Cookie;

class CartService {
    protected $session;
    protected $articleRepository;
    protected $ordersRepository;
    protected $cartRepository;
    protected $manager;
    private $security;

    public function __construct(SessionInterface $session, ArticleRepository $articleRepository, OrdersRepository $ordersRepository, CartRepository $cartRepository, EntityManagerInterface $manager, Security $security) {
        $this->session = $session;
        $this->articleRepository = $articleRepository;
        $this->ordersRepository = $ordersRepository;
        $this->cartRepository = $cartRepository;
        $this->manager = $manager;
        $this->security = $security;
    }

    public function getCart() : array {
        
        if ($this->session->get('cart')) {
            foreach ($this->session->get('cart') as $itemId => $quantity) {
                $cart[] = [
                    'article' => $this->articleRepository->find($itemId),
                    'quantity' => $quantity
                ];
            }
        }
        $cartItems = $cart ?? [];

        return [
            'items'=> $cartItems,
            'total'=> count($cartItems)
        ];
    }
    
    public function add(Request $request) : array {   
        $articleId = $request->request->get('articleId');
        $quantity = (int) $request->request->get('quantity');
        $article = $this->articleRepository->find($articleId);

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$articleId])) {
            $cart[$articleId] += $quantity;
        } else {
            $cart[$articleId] = $quantity;
        }

        if ($cart[$articleId] > 5) {
            $cart[$articleId] = 5;
        }
        
        // STORAGE DD OR COOKIES ===============================================================
            $user = $this->security->getUser();
            if (!$user) {
                //no user, stock addition into cookies
                
            } else {
                //user exists, check if an order is already ongoing (no validation date) 
                if (!$this->ordersRepository->findOnGoingOrderByUser($user)) {
                    //no ongoing order, so create it
                    $newOrder = new Orders();
                    $newOrder->setAccount($user);
                    $this->manager->persist($newOrder);
                    $this->manager->flush();
                }

                //get the ongoing order id
                $order = $this->ordersRepository->findOnGoingOrderByUser($user);

                //check if the article was already added by the user in the cart of the ongoing order
                if ($this->cartRepository->findArticleInOngoingOrder($order,$article)) {
                    $cartItem = $this->cartRepository->findArticleInOngoingOrder($order,$article);
                } else {
                    $cartItem = new Cart();
                    $cartItem->setOrders($order);
                    $cartItem->setArticle($article);
                }

                $cartItem->setQuantity($cart[$articleId]);
                $this->manager->persist($cartItem);
                $this->manager->flush();
            }
        // END STORAGE DB OR COOKIES ====================================================================
        
        $this->session->set('cart', $cart);

        return [
            'title' =>  $article->getArticleTitle(),
            'image' => $article->getImages()[0]->getUrl(),
            'itemsInCart' => count($cart),
            'quantity' => $quantity == 0 ? 1 : $quantity
        ];
    }

    public function remove($request) :array {
        $articleId = (int) $request->request->get('articleId');
        $article = $this->articleRepository->find($articleId);
        
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$articleId])) {
            unset($cart[$articleId]);
        }

        // STORAGE DD OR COOKIES ===============================================================
            $user = $this->security->getUser();
            if (!$user) {
                //no user, stock addition into cookies
                
            } else {
                //if need to remove => ongoing order exists
                $order = $this->ordersRepository->findOnGoingOrderByUser($user);

                //find the article to remove from the cart
                $cartItem = $this->cartRepository->findArticleInOngoingOrder($order,$article);
                $order->removeCart($cartItem);
                $this->manager->persist($order);
                $this->manager->flush();
            }
        // END STORAGE DB OR COOKIES ====================================================================

        $this->session->set('cart', $cart);

        return [
            'articleId' => $articleId,
            'itemsInCart' => count($cart)
        ];
    }

    public function modifyArticleQuantity($request) {
        $articleId = (int) $request->request->get('articleId');
        $article = $this->articleRepository->find($articleId);
        $quantity = (int) $request->request->get('quantity');

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$articleId])) {
            $cart[$articleId] = $quantity;
        }

        // STORAGE DD OR COOKIES ===============================================================
            $user = $this->security->getUser();
            if (!$user) {
                //no user, stock addition into cookies
                
            } else {
                //if need to remove => ongoing order exists
                $order = $this->ordersRepository->findOnGoingOrderByUser($user);

                //find the article to remove from the cart
                $cartItem = $this->cartRepository->findArticleInOngoingOrder($order,$article);
                $cartItem->setQuantity($quantity);
                
                $this->manager->persist($cartItem);
                $this->manager->flush();
            }
        // END STORAGE DB OR COOKIES ====================================================================

        $this->session->set('cart', $cart);

        return [
            'articleId' => $articleId,
            'quantity' => $quantity
        ];
    }
}