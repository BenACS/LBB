<?php

namespace App\Controller;

use App\Service\Header\HeaderService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(HeaderService $header)
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'header' => $header
        ]);
    }
}
