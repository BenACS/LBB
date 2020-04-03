<?php

namespace App\Controller;

use App\Entity\Product;
use App\Data\Cart\CartData;
use App\Service\Cart\CartService;

use App\Service\Header\TagService;
use App\Repository\ArticleRepository;

use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    protected $header;
    protected $cart;
    protected $serializer;
    private $session;

    public function __construct(HeaderService $header, CartService $cart, SerializerInterface $serializer, SessionInterface $session)
    {
        $this->header = $header;
        $this->cart = $cart;
        $this->serializer = $serializer;
        $this->session = $session;
    }
    /**
     * @Route("/cart/recap", name="cart")
     */
    public function index()
    {
        return $this->render('cart/index.html.twig', [
            'header' => $this->header,
            'cart' => $this->cart->getCart(),
            'index' => 0
        ]);
    }

    /**
     * Add an article into the cart
     * @Route("/product/addToCart", name="cart_add")

     * @param Request $request
     * @return Response
     */
    public function addToCart(Request $request): Response
    {
        $addition = $this->serializer->deserialize($request->getContent(), CartData::class, 'json');
        return $this->json($this->cart->add($addition), 200);
    }

    /**
     * Remove an article from the cart
     * @Route("/product/removeFromCart", name="cart_remove")

     * @param Request $request
     * @return Response
     */
    public function removeFromCart(Request $request): Response
    {
        $removal = $this->serializer->deserialize($request->getContent(), CartData::class, 'json');
        return $this->json($this->cart->remove($removal), 200);
    }

    /**
     * Modify quantity of the selected article
     * @Route("/product/modifyCart", name="cart_modify")

     * @param Request $request
     * @return Response
     */
    public function modifyArticleQuantity(Request $request): Response
    {
        $modification = $this->serializer->deserialize($request->getContent(), CartData::class, 'json');
        return $this->json($this->cart->setQuantity($modification), 200);
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

    /**
     * @Route("/cart/checkout", name="cart_checkout")
     *
     * @param Request $request
     * @return Response
     */
    public function checkout(): Response
    {

        if ($this->getUser()) {
            // dump($this->cart->getCart());
            return $this->render('cart/delivery.html.twig', [
                'header' => $this->header,
                'cart' => $this->cart->getCart(),
                'user' => $this->getUser(),
                'index' => 1
            ]);
        } else {
            $response = new Response(
                'Content',
                Response::HTTP_OK,
                ['content-type' => 'text/html']
            );
            $cookie = new Cookie('logFromCart', 'delivery', \time()+5*60);
            $response->headers->setCookie($cookie);
            $response->send();
            return $this->redirectToRoute('security_login');
        }

        // return $this->json([
        //     'request' => $request->request->all()
        // ], 200);
    }
    /**
     * @Route("/cart/confirm", name="cart_confirm")
     *
     * @param Request $request
     * @return Response
     */
    public function confirmCart(Request $request)
    {
        if ($this->getUser()) {
            $manager = $this->getDoctrine()->getManager();
            $order = $this->cart->getOngoingOrder($this->getUser());
            $articles = $this->cart->getCart()["items"];
            $totalPrice = 0;

            foreach ($articles as $key => $value) {
                $quantity = 0;
                foreach ($value as $newKey => $newValue) {
                    if ($newKey === 'article') {
                        $priceArticle = $newValue->getProduct()->getPrice()->getPriceDf();
                    } elseif ($newKey === 'quantity') {
                        $quantity = $newValue;
                        $totalPrice += $quantity * $priceArticle;
                    }
                }
            }
            $totalPrice += $request->request->get('deliveryChoice');

            $order->setTotalValue($totalPrice);
            $order->setValidationDate(new \DateTime());
            $order->setOrderNumber("CMD" . $order->getValidationDate()->format('Y-m-d-H-i-s') . "-" . $this->getUser()->getId());
            $manager->persist($order);
            $manager->flush();

            $this->session->set('cart', []);

            return $this->redirectToRoute('success');
        } else {
            return $this->redirectToRoute('error');
        }
    }
}
