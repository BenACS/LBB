<?php

namespace App\Controller;

use App\Service\Header\TagService;
use App\Service\Header\HeaderService;
use App\Repository\CategoryRepository;

use App\Service\Article\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(HeaderService $header, CategoryRepository $catRepo, ArticleService $product)
    {
        dump($this->session);
        return $this->render('home/index.html.twig', [
            'header' => $header,
            'latest' => $product->getLatestProducts(),
            'hottest' => $product->getHottestProducts()
        ]);
    }

    /**
     * @Route("/searchTag/tag", name="searchTag")
     * 
     */
    public function searchTag(HeaderService $header, Request $request)
    {
        foreach ($header->getTagNamesArray() as $id => $name) {
            if ($request->request->get('tag') == $name) {
                $tagId = $id + 1;
                $subCatName = $header->getTagCategory($tagId)->getCategoryName();
                $subParentId = $header->getTagCategory($tagId)->getParentId();
                if ($subParentId != 0) {
                    $catName = $header->getMainCatName($subParentId);
                }
            }
        }
        if (!isset($tagId)) {
            return $this->redirectToRoute('error');
        }

        if (isset($catName)) {
            return $this->redirectToRoute(
                'subcategories',
                [
                    'category' => $catName,
                    'subcategories' => $subCatName
                ]
            );
        } else {
            return $this->redirectToRoute(
                'category',
                [
                    'category' => $subCatName
                ]
            );
        }
    }

    /**
     * 
     * @Route("/error", name="error")
     */
    public function errorPage(HeaderService $header)
    {
        return $this->render('errorPage/index.html.twig', [
            'header' => $header
        ]);
    }

    /**
     * 
     * @Route("/success", name="success")
     */
    public function successPage(HeaderService $header)
    {
        return $this->render('successPage/index.html.twig', [
            'header' => $header
        ]);
    }
}
