<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Account;
use App\Form\RegistrationType;
use App\Service\Cart\CartService;

use App\Service\Header\TagService;
use App\Form\RegistrationTypeTwoType;
use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
{
    private $session;
    private $header;

    public function __construct(SessionInterface $session, HeaderService $header)
    {
        $this->session = $session;
        $this->header = $header;
    }
    /**
     * @Route("/authentification", name="authentification")
     */
    public function index(Request $request)
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
            'header' => $this->header,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/authentificationTwo", name="authentificationTwo")
     */
    public function indexSequel(Request $request, UserPasswordEncoderInterface $encoder)
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
            'header' => $this->header,
            'form2' => $form2->createView(),
            'method' => 'POST'
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(Request $request)
    {
        if ($request->cookies->get('logFailed')) {
            $logFailed = true;
            $response = new Response();
            $response->headers->clearCookie('logFailed');
            $response->send();
        } else {
            $logFailed = false;
        }

        return $this->render('authentification/login.html.twig', [
            'header' => $this->header,
            'logFailed' => $logFailed
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
            $response = new Response();
            $response->headers->clearCookie('logFromCart');
            $response->send();

            return $this->redirectToRoute('cart');

        } elseif ($request->cookies->get('logFromProduct')) {

            return $this->redirectToRoute('product', ['id' => $request->cookies->get('logFromProduct')]);

        } else {

            return $this->redirectToRoute("home");
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('authentification/login.html.twig', [
            'header' => $this->header
        ]);
    }

    /**
     * @Route("/logFailed", name="log_failed")
     */
    public function logFailed() {
        $response = new Response(
            'Content',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
        $cookie = new Cookie('logFailed', true, \time()+3*60);
        $response->headers->setCookie($cookie);
        $response->send();

        return $this->redirectToRoute('security_login');
    }
}
