<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Account;
use App\Form\AdressType;
use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $session;
    private $security;

    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }
    /**
     * @Route("/profil", name="profil")
     */
    public function index(HeaderService $header, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $userId = $this->security->getUser();
        // dd($userId);
        $userAdress = new Adress();

        $form = $this->createForm(AdressType::class, $userAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userAdress->setAccount($userId);
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
