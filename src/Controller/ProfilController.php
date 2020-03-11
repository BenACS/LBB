<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/profil/profil/profil", name="profil")
     */
    public function index(HeaderService $header, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $userAdress = new Adress();
        dd($this->session);
        $form = $this->createForm(AdressType::class, $userAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($userAdress);
            $manager->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'header' => $header,
            'form' => $form->createView()
        ]);
    }
}
