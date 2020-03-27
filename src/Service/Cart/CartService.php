<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\Orders;

use App\Entity\Account;
use App\Entity\Article;

use App\Data\Cart\CartData;
use App\Repository\CartRepository;

use App\Repository\OrdersRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $articleRepository;
    protected $ordersRepository;
    protected $cartRepository;
    protected $manager;
    private $security;

    public function __construct(SessionInterface $session, ArticleRepository $articleRepository, OrdersRepository $ordersRepository, CartRepository $cartRepository, EntityManagerInterface $manager, Security $security)
    {
        $this->session = $session;
        $this->articleRepository = $articleRepository;
        $this->ordersRepository = $ordersRepository;
        $this->cartRepository = $cartRepository;
        $this->manager = $manager;
        $this->security = $security;
    }

    public function getOngoingOrder(Account $user): Orders
    {
        if (!$this->ordersRepository->findOnGoingOrderByUser($user)) {
            //no ongoing order, so create it
            $newOrder = new Orders();
            $newOrder->setAccount($user);
            $this->manager->persist($newOrder);
            $this->manager->flush();
        }
        return $this->ordersRepository->findOnGoingOrderByUser($user);
    }

    public function addIntoDB(Account $user, Article $article, int $quantity)
    {
        $order = $this->getOngoingOrder($user);

        if ($this->cartRepository->findArticleInOngoingOrder($order, $article)) {
            $cartItem = $this->cartRepository->findArticleInOngoingOrder($order, $article);
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

    public function setNewQuantityIntoDB(Account $user, Article $article, int $quantity)
    {
        $order = $this->getOngoingOrder($user);
        $cartItem = $this->cartRepository->findArticleInOngoingOrder($order, $article);
        $cartItem->setQuantity($quantity);
        $this->manager->persist($cartItem);
        $this->manager->flush();
    }

    /**
     * Remove article from DB
     *
     * @param Orders $order
     * @param Article $article
     * @return void
     */
    private function removeFromDB(Account $user, Article $article)
    {
        //if need to remove => ongoing order exists
        $order = $this->ordersRepository->findOnGoingOrderByUser($user);

        $cartItem = $this->cartRepository->findArticleInOngoingOrder($order, $article);
        $order->removeCart($cartItem);
        $this->manager->persist($order);
        $this->manager->flush();
    }

    public function getCart(): array
    {

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
            'items' => $cartItems,
            'total' => count($cartItems)
        ];
    }

    /**
     * Add a new article into the cart or modify its quantity
     *
     * @param Request $request
     * @param string $action, set to 'setQuantity' to access to the modifyQuantity functionality
     * @return array
     */
    public function add(CartData $addition): array
    {
        $articleId = $addition->articleId;
        $quantity = $addition->quantity ?? 1;
        $article = $this->articleRepository->find($articleId);

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$articleId])) {
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
            //check if the article was already added by the user in the cart of the ongoing order
            $this->addIntoDB($user, $article, $cart[$articleId]);
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

    public function setQuantity(CartData $modification): array
    {
        $articleId = $modification->articleId;
        $quantity = $modification->quantity;
        $article = $this->articleRepository->find($articleId);

        $cart = $this->session->get('cart', []);

        $cart[$articleId] = $quantity;

        // STORAGE DD OR COOKIES ===============================================================
        $user = $this->security->getUser();
        if ($user) {
            //check if the article was already added by the user in the cart of the ongoing order
            $this->setNewQuantityIntoDB($user, $article, $cart[$articleId]);
        }
        // END STORAGE DB OR COOKIES ====================================================================

        $this->session->set('cart', $cart);

        return [];
    }

    public function remove(CartData $removal): array
    {
        $articleId = $removal->articleId;
        $article = $this->articleRepository->find($articleId);

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$articleId])) {
            unset($cart[$articleId]);
        }

        // STORAGE DD OR COOKIES ===============================================================
        $user = $this->security->getUser();
        if ($user) {
            //find the article to remove from the cart
            $this->removeFromDB($user, $article);
        }
        // END STORAGE DB OR COOKIES ====================================================================

        $this->session->set('cart', $cart);

        return [
            'articleId' => $articleId,
            'itemsInCart' => count($cart)
        ];
    }

    public function getUserCart(Account $user)
    {
        // PUSH SESSION CART INTO DB
        //get session cart
        $cart = $this->session->get('cart', []);

        if (count($cart) != 0) {
            //get ongoing order id
            foreach ($cart as $articleId => $quantity) {
                $article = $this->articleRepository->find($articleId);
                $this->addIntoDB($user, $article, $quantity);
            }
        }

        $order = $this->ordersRepository->findOnGoingOrderByUser($user);


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
