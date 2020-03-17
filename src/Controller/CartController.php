<?php

namespace App\Controller;

use App\Data\Cart\CartData;
use App\Service\Cart\CartService;
use App\Service\Header\TagService;

use App\Repository\ArticleRepository;
use App\Service\Header\HeaderService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends AbstractController
{
    protected $header;
    protected $cart;
    protected $serializer;

    public function __construct(HeaderService $header, CartService $cart,SerializerInterface $serializer) {
        $this->header = $header;
        $this->cart = $cart;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/cart/recap", name="cart")
     */
    public function index()
    {
        return $this->render('cart/index.html.twig', [
            'header' => $this->header,
            'cart' => $this->cart->getCart()
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
    public function addToCart(Request $request): Response
    {
        $addition = $this->serializer->deserialize($request->getContent(), CartData::class,'json');
        return $this->json($this->cart->add($addition) , 200);

    }

    /**
     * Remove an article from the cart
     * @Route("/product/removeFromCart", name="cart_remove")

     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function removeFromCart(Request $request): Response
    {
        $removal = $this->serializer->deserialize($request->getContent(), CartData::class,'json');
        return $this->json($this->cart->remove($removal), 200);
    }

    /**
     * Modify quantity of the selected article
     * @Route("/product/modifyCart", name="cart_modify")

     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function modifyArticleQuantity(Request $request): Response
    {
        $modification = $this->serializer->deserialize($request->getContent(), CartData::class,'json');
        return $this->json($this->cart->add($modification,'setQuantity'), 200);
    }

    /**
     * Function only for test purpose
     * @Route("/product/showCart", name="cart_show")

     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function giveMeCart(Request $request, SessionInterface $session): Response
    {
        $cart = $session->get('cart');

        return $this->json([
            'cart' => $cart
        ], 200);
    }
}
