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
    protected $header;
    private $session;

    public function __construct(HeaderService $header, SessionInterface $session) {
        $this->header = $header;
        $this->session = $session;
    }
    
    /**
     * @Route("/", name="home")
     */
    public function index( ArticleService $product)
    {
        // dump($this->session); RESPONSABLE !
        return $this->render('home/index.html.twig', [
            'header' => $this->header,
            'latest' => $product->getLatestProducts(),
            'hottest' => $product->getHottestProducts()
        ]);
    }

    /**
     * @Route("/searchTag/tag", name="searchTag")
     * 
     */
    public function searchTag(Request $request)
    {
        foreach ($this->header->getTagNamesArray() as $id => $name) {
            if ($request->request->get('tag') == $name) {
                $tagId = $id + 1;
                $subCatName = $this->header->getTagCategory($tagId)->getCategoryName();
                $subParentId = $this->header->getTagCategory($tagId)->getParentId();
                if ($subParentId != 0) {
                    $catName = $this->header->getMainCatName($subParentId);
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
    public function errorPage()
    {
        return $this->render('errorPage/index.html.twig', [
            'header' => $this->header
        ]);
    }

    /**
     * 
     * @Route("/success", name="success")
     */
    public function successPage()
    {
        return $this->render('successPage/index.html.twig', [
            'header' => $this->header
        ]);
    }
}
