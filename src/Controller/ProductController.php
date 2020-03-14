<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Price;
use App\Entity\Review;
use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ReviewFormType;
use App\Service\Header\TagService;
use App\Service\Header\HeaderService;
use App\Service\Article\ArticleService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/product/{id?0}", name="product", requirements={"id"="\d+"})
     */
    public function index(int $id = 0, Product $product = null, HeaderService $header, ArticleService $article, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $averageRating = null;

        if ($id == 0 || !isset($product)) {
            return $this->redirectToRoute("home");
        }
        $this->session->set('productId', $product->getId());
        if ($this->getUser()) {
            $this->session->set('userLogged', 'true');
        }
        dump($this->session);

        $review = new Review();
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review->setCreationDate(new \DateTime());
            $review->setProduct($product);
            $review->setAccount($this->getUser());
            $manager->persist($review);
            $manager->flush();

            return $this->redirectToRoute('product', ['id' => $product->getId()]);
        }

        if ($product->getAverageRate() !== null) {
            $averageRating = array_sum($product->getAverageRate()) / count($product->getAverageRate());
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'price' => $product->getPrice(),
            'variations' => ['sizes' => $product->getAllSizes(), 'colors' => $product->getAllColors(), 'devices' => $product->getAllDevices()],
            'images' => $article->getAllImages($id),
            'header' => $header,
            'article' => $product->getArticles()[0],
            'form' => $form->createView(),
            'reviews' => $product->getReviews(),
            'user' => $this->getUser(),
            'averagerating' => $averageRating
        ]);
    }

    /**
     * @Route("/editReview/{idReview?0}", name="editReview", requirements={"id"="\d+"})
     */
    public function editReview(int $idReview = 0, HeaderService $header, Request $request)
    {

        $manager = $this->getDoctrine()->getManager();

        foreach ($this->getUser()->getReviews() as $review) {
            if ($review->getId() === $idReview) {
                $reviewToEdit = $review;
            }
        }

        $reviewComment = $request->request->get('reviewComment');
        $reviewRatingEdited = $request->request->get('reviewRating');

        if (isset($reviewComment)) {
            $reviewToEdit->setComment($reviewComment);
            if ($reviewRatingEdited != $reviewToEdit->getRating()) {
                $reviewToEdit->setRating($reviewRatingEdited);
            }
            $manager->persist($reviewToEdit);
            $manager->flush();

            return $this->redirectToRoute('product', ['id' => $reviewToEdit->getProduct()->getId()]);
        }

        if ($this->session->get('userLogged')) {
            return $this->render('product/editReview.html.twig', [
                'header' => $header,
                'reviewtoedit' => $reviewToEdit
            ]);
        }
    }

    /**
     * @Route("/removeReview/{idReview?0}", name="removeReview", requirements={"id"="\d+"})
     */
    public function removeReview(int $idReview = 0, HeaderService $header)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($this->session->get('userLogged')) {
            foreach ($user->getReviews() as $review) {
                if ($review->getId() === $idReview) {
                    $reviewToRemoveId = $review;
                    $reviewToremove = $user->removeReview($review);
                }
            }

            $manager->persist($user);
            $manager->flush();
        }

        return $this->redirectToRoute('product', ['id' => $this->session->get('productId')]);
    }

    /**
     * Check which article was selected
     * @Route("/product/{id}/checkArticle", name="product_checkArticle", requirements={"id"="\d+"})

     * @param integer $id
     * @param ArticleService $articleService
     * @return Response
     */
    public function checkArticle(int $id, Request $request, ArticleService $articleService): Response
    {

        $article = $articleService->getArticleInfos($id, $request->request->all());

        return $this->json([
            'articleId' => $article->getId(),
            'stockMessage' => $article->getStockMessage(),
            'stock' => $article->getStock()
        ], 200);
    }
}
