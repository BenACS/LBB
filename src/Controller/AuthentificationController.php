<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Account;
use App\Form\RegistrationType;
use App\Service\Header\TagService;

use App\Form\RegistrationTypeTwoType;
use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/authentification", name="authentification")
     */
    public function index(HeaderService $header, Request $request)
    {

        $manager = $this->getDoctrine()->getManager();
        $user = new Account();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->session->set('userAccount', $user);

            return $this->redirectToRoute('authentificationTwo');
        }

        return $this->render('authentification/index.html.twig', [
            'header' => $header,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/authentificationTwo", name="authentificationTwo")
     */
    public function indexSequel(HeaderService $header, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $userEmail = $this->session->get('userAccount')->getEmail();
        $userPassword = $this->session->get('userAccount')->getPassword();

        $user = new Account();

        $form2 = $this->createForm(RegistrationTypeTwoType::class, $user);

        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {


            $user->setEmail($userEmail);
            // $user->setPassword($userPassword);
            $hash = $encoder->encodePassword($user, $userPassword);
            $user->setPassword($hash);
            $user->setRegisterDate(new \DateTime());
            $user->setRole('user');


            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }


        return $this->render('authentification/sequel.html.twig', [
            'header' => $header,
            'form2' => $form2->createView(),
            'method' => 'POST'
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(HeaderService $header)
    {
        return $this->render('authentification/login.html.twig', [
            'header' => $header
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(HeaderService $header)
    {
        return $this->render('authentification/login.html.twig', [
            'header' => $header
        ]);
    }
}
