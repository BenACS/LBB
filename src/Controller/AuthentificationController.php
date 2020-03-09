<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Header\HeaderService;
use App\Service\Header\TagService;

class AuthentificationController extends AbstractController
{
    /**
     * @Route("/authentification", name="authentification")
     */
    public function index(HeaderService $header)
    {
        return $this->render('authentification/index.html.twig', [
            'controller_name' => 'AuthentificationController',
            'header' => $header
        ]);
    }
}
