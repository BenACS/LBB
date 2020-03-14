<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\Orders;

use App\Entity\Account;
use App\Entity\Article;

use App\Repository\CartRepository;
use App\Repository\OrdersRepository;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    private function getOngoingOrder(Account $user) : Orders {
        if (!$this->ordersRepository->findOnGoingOrderByUser($user)) {
            //no ongoing order, so create it
            $newOrder = new Orders();
            $newOrder->setAccount($user);
            $this->manager->persist($newOrder);
            $this->manager->flush();
        }
        return $this->ordersRepository->findOnGoingOrderByUser($user);
    }

    private function addCartItem(Orders $order, Article $article, int $quantity) {
        if ($this->cartRepository->findArticleInOngoingOrder($order,$article)) {
            $cartItem = $this->cartRepository->findArticleInOngoingOrder($order,$article);
            $quantity += $cartItem->getQuantity();
            if ($quantity > 5) {
                $quantity = 5;
            }
        } else {
            $cartItem = new Cart();
            $cartItem->setOrders($order);
            $cartItem->setArticle($article);
        }

        $cartItem->setQuantity($quantity);
        $this->manager->persist($cartItem);
        $this->manager->flush();
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
    
    /**
     * Add a new article into the cart or modify its quantity
     *
     * @param Request $request
     * @param string $action, set to 'setQuantity' to access to the modifyQuantity functionality
     * @return array
     */
    public function add(Request $request, string $action = 'add') : array {   
        $articleId = $request->request->get('articleId');
        $quantity = (int) $request->request->get('quantity');
        $article = $this->articleRepository->find($articleId);

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$articleId]) && $action == 'add') {
            $cart[$articleId] += $quantity;
            if ($cart[$articleId] > 5) {
                $cart[$articleId] = 5;
            }
        } else {
            $cart[$articleId] = $quantity;
        }

        // STORAGE DD OR COOKIES ===============================================================
            $user = $this->security->getUser();
            if ($user) {
                //user exists, check if an order is already ongoing (no validation date) 
                $order = $this->getOngoingOrder($user);

                //check if the article was already added by the user in the cart of the ongoing order
                $this->addCartItem($order,$article,$cart[$articleId]);
            }
        // END STORAGE DB OR COOKIES ====================================================================
        
        $this->session->set('cart', $cart);

        return [
                'title' =>  $article->getArticleTitle(),
                'image' => $article->getImages()[0]->getUrl(),
                'itemsInCart' => count($cart),
                'articleId' => $articleId,
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
            if ($user) {
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

    public function getUserCart(Account $user) {
        // PUSH SESSION CART INTO DB
            //get session cart
            $cart = $this->session->get('cart', []);

            if (count($cart) !=0) {
                //get ongoing order id
                $order = $this->getOngoingOrder($user);
                foreach ($cart as $articleId => $quantity) {
                    $article = $this->articleRepository->find($articleId);
                    $this->addCartItem($order, $article, $quantity);
                }
            } else {
                $order = $this->ordersRepository->findOnGoingOrderByUser($user);
            }
            
        
        // GET NEW USER CART
        if ($order) {
            $cartFromDB = $this->cartRepository->findOrderCart($order);
        
            foreach ($cartFromDB as $item) {
                $cartUser[$item->getArticle()->getId()] = $item->getQuantity();
            }
        }       
        

        $this->session->set('cart', $cartUser ?? []);
    }

    
}