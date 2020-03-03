<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\Price;
use App\Entity\ArticleImages;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Header\HeaderService;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product", requirements={"id"="\d+"})
     */
    public function index(int $id = 0, Product $product = null, HeaderService $cat)
    {

        if ($id == 0 || !isset($product)) {
            return $this->redirectToRoute("home");
        }
        foreach ($product->getArticles() as $article) {
            foreach ($article->getArticleImages() as $image) {
                if (!isset($images) || !in_array($image->getURL(), $images)) {
                    $images[] = $image->getUrl();
                }
            }

            if ($article->getSize() !== null && (!isset($sizes) || (!in_array($article->getSize(), $sizes)))) {
                $sizes[] = $article->getSize();
            } elseif ($article->getSize() === null) {
                $sizes = null;
            }

            if ($article->getColor() !== null && (!isset($colors) || (!in_array($article->getColor(), $colors)))) {
                $colors[] = $article->getColor();
            } elseif ($article->getColor() === null) {
                $colors = null;
            }

            if ($article->getDevice() !== null && (!isset($devices) || (!in_array($article->getDevice(), $devices)))) {
                $devices[] = $article->getDevice();
            } elseif ($article->getDevice() === null) {
                $devices = null;
            }
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'price' => $product->getPrice(),
            'variations' => ['sizes' => $sizes, 'colors' => $colors, 'devices' => $devices],
            'articleImages' => $images,
            'categories' => $cat->createHeader()
        ]);
    }
}
