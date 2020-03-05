<?php

namespace App\Controller;

use App\Repository\ArticleRepository;

use App\Service\Header\HeaderService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(HeaderService $cat)
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'categories' => $cat->createHeader()
        ]);
    }

    /**
     * Undocumented function
     * @Route("/product/addToCart", name="cart_add")

     * @param Request $request
     * @return Response
     */
    public function addToCart(Request $request, ArticleRepository $articleRepo) : Response {

        $article = $articleRepo->find($request->request->get("articleId"));

        return $this->json([
            'title' =>  $article->getArticleTitle(),
            'image' => $article->getProduct()->getAllUniqueImages()[0],
            'quantity' => $request->request->get("quantity"),
            'itemsInCart' => 1
            ],200);
    }
}