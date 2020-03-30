<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Account;
use App\Form\RegistrationType;
use App\Service\Header\TagService;

use App\Form\RegistrationTypeTwoType;
use App\Service\Cart\CartService;
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
            $hash = $encoder->encodePassword($user, $userPassword);
            $user->setPassword($hash);
            $user->setRegisterDate(new \DateTime());

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
    public function login(HeaderService $header, Request $request)
    {
        return $this->render('authentification/login.html.twig', [
            'header' => $header
        ]);
    }
    /**
     * @Route("/login_success", name="login_success")
     */
    public function postLoginRedirectAction(CartService $cartService, Request $request)
    {
        // UPDATE AND GET SESSION CART
        $cartService->getUserCart($this->getUser());

        if ($request->cookies->get('logFromCart')) {

            return $this->redirectToRoute('cart');

        } elseif ($this->session->get('logFromProduct')) {

            return $this->redirectToRoute('product', ['id' => $this->session->get('logFromProduct')]);

        } else {

            return $this->redirectToRoute("home");
        }
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
